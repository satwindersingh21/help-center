<?php

namespace App\Controller\Admin;

use Cake\Auth\DefaultPasswordHasher;

/**
 * Admins Controller
 *
 * @property \App\Model\Table\AdminsTable $Admins
 *
 * @method \App\Model\Entity\Admin[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AdminsController extends AppController {
    
    
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['login', 'forgotPassword', 'resetPassword', 'add', 'changeStatus']);
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }
    }
    
    public function login() {
        //if already logged-in, redirect
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }
        if ($this->request->is('post')) {
            $admin = $this->Auth->identify();
            if ($admin) {

                $this->Auth->setUser($admin);
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('Email or password is incorrect'));
                $this->redirect('/admin');
            }
        } elseif (empty($this->data)) {
            $rememberToken = $this->Cookie->read('aptnet_remember_token');
            if (!is_null($rememberToken)) {
                $rememberToken = explode("^", $rememberToken);
                $data = $this->Admins->find('all', ['conditions' => ['remember_token' => $rememberToken[0]]], ['fields' => ['email', 'password']])->first();
                
                $this->request->getData()['email'] = $data->email;
                $this->request->getData()['password'] = base64_decode($rememberToken[1]);
                $admin = $this->Auth->identify();
                if ($admin) {
                    $this->Auth->setUser($admin);
                    $this->redirect($this->Auth->redirectUrl());
                } else {
                    $this->redirect('/admin');
                }
            }
        }
    }
    
    public function dashboard() {

        
    }
    
    public function changePassword() {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $admin = $this->Admins->find()->where(['id' => $this->Auth->user('id')])->first();
            if ((new DefaultPasswordHasher)->check($this->request->getData('current_password'), $admin->password)) {
                if ($this->request->getData('new_password') == $this->request->getData('confirm_password')) {
                    $admin->password = $this->request->getData('new_password');
                    if ($this->Admins->save($admin)) {
                        $this->Flash->success(__('Password has been reset.'));
                    } else {
                        $this->Flash->error(__('Password has not been set.'));
                    }
                } else {
                    $this->Flash->error(__('Confirm Password does not match with New Password'));
                }
            } else {
                $this->Flash->error(__('Invalid Current Password'));
            }
        }
        return $this->redirect(['controller' => 'admins', 'action' => 'profile']);
    }
    
    public function logout() {
        $this->Auth->logout();
        $this->request->getSession()->destroy();
        $this->Cookie->delete('aptnet_remember_token');
        $this->Flash->success(__('You are now logged out'));
        return $this->redirect(['controller' => 'Admins', 'action' => 'login']);
    }
    
    public function profile() {
        
        $admin = $admin = $this->Admins->get($this->Auth->user('id'), ['contain' => ['Images']]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            
            $admin = $this->Admins->patchEntity($admin, $this->request->getData());
            
            
            if ($this->Admins->save($admin)) {
                $this->Flash->success(__('The admin has been saved.'));
                $admin = $this->Admins->get($admin->id, ['contain' => ['Images']]);
                $this->Auth->setUser($admin);
                
                return $this->redirect(['action' => 'profile']);
            } else {
                //pr($admin->errors()); die;
            }
            $this->Flash->error(__('The admin could not be saved. Please, try again.'));
        }
        unset($admin->password);
        $this->set(compact('admin'));
    }
    
    public function changeStatus() {
        
        sleep(1);
        
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        if ($this->request->is('post')) {
            $model = $this->request->getData('model');
            $field = $this->request->getData('field');
            $id = $this->request->getData('id');
            
            $this->loadModel($model);
            
            $entity = $this->{$model}->find('all')->where(['id' => $id])->first();
            
            $entity->{$field} = !$entity->{$field};
            
            if ($this->{$model}->save($entity)) {
                if ($model == "Subscriptions") {
                    $this->loadModel('Users');
                    $user = $this->Users->find('all')->where(['id' => $entity->user_id])->first();
                    $user->has_plan = ($entity->{$field}) ? 1 : 0;
                    $this->Users->save($user);
                }
                $this->responseCode = SUCCESS_CODE;
                $this->responseData['new_status'] = $entity->{$field};
            }
        }
        
        echo $this->responseFormat();
    }
    
    
    public function getOptions() {
        $this->autoRender = false;
        $query = $this->request->getData('query');
        if (!empty($query)) {
            
            $value = empty($this->request->getData('value')) ? "id" : $this->request->getData('value');
            $label = empty($this->request->getData('label')) ? "name" : $this->request->getData('label');
            $match = $this->request->getData('match');
            $model = $this->request->getData('find');
            
            $this->loadModel($model);
            
            $options = $this->{$model}
                ->find('all')
                ->select(['value' => $model . "." . $value, 'label' => $model . "." . $label])
                ->where([$model . "." . $match => $query])
                ->where([$model . '.status' => true])
                ->all()
                ->toArray();
            echo json_encode(['suggestions' => $options]);
        } else {
            echo json_encode(['suggestions' => []]);
        }
        
        exit;
    }
    
    public function getSuggestions() {
        $this->autoRender = false;
        $query = $this->request->getQuery('query');
        if (!empty($query)) {
            $model = $this->request->getQuery('find');
            $this->loadModel($model);
            $match = empty($this->request->getQuery('match')) ? "name" : $this->request->getQuery('match');
            
            $matches = explode(",", $match);
            foreach ($matches as $m) {
                $conditions['OR'][$model . '.' . $m . ' LIKE'] = '%' . $query . '%';
            }
            
            $select = empty($this->request->getQuery('select')) ? $model . ".name" : $this->request->getQuery('select');
            
            if (!empty($this->request->getQuery('conditions'))) {
                foreach ($this->request->getQuery('conditions') as $field => $value) {
                    $conditions[$field] = $value;
                }
            }
            
            $cities = $this->$model
                ->find('all')
                ->select([$model . '.id', 'value' => $select])
                ->where($conditions)
                ->contain([])
                ->toArray();
            echo json_encode(['suggestions' => $cities]);
        } else {
            echo json_encode(['suggestions' => []]);
        }
        
        exit;
    }
    
    public function isUniqueEmail($id = null) {
        $this->autoRender = false;
        $this->loadModel('Users');
        if ($id === null) {
            $email = $this->request->getQuery('email');
            if ($this->Users->findByEmail($email)->count()) {
                $alreadyExists = "false";
            } else {
                $alreadyExists = "true";
            }
        } else {
            $count = $this->Users->find()
                ->where(['id !=' => $id, 'email' => $this->request->getQuery('email')])
                ->count();
            if ($count) {
                $alreadyExists = "false";
            } else {
                $alreadyExists = "true";
            }
        }
        echo $alreadyExists;
        exit;
    }
    
}
