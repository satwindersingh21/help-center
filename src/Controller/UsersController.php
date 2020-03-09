<?php

namespace App\Controller;


/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['index', 'register', 'login', 'home', 'isUniqueEmail', 'add', 'index']);
    }

    //ALTER TABLE  `users` ADD  `occupation_id` INT NOT NULL AFTER  `id` ;

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        if ($this->Auth->user()) {
            // return $this->redirect($this->Auth->redirectUrl());
        }

        $this->paginate = ['contain' => []];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }


    /**
     * logout method
     */
    public function logout() {
        $this->Cookie->delete('loggedInUser');
        $this->Flash->success(__('You are now logged out'));
        return $this->redirect($this->Auth->logout());
    }

    public function login() {
        //if already logged-in, redirect
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }


        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
            } else {
                $this->Flash->error(__('Invalid username or password, try again'));
            }
        }
    }

    /**
     * Register method
     *
     * @return \Cake\Http\Response|null Redirects to Auth Redirect URL.
     */
    public function register() {
        //if already logged-in, redirect
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {

            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {

                $this->Auth->setUser($user);

                $this->Flash->success(__('Successfully Registered.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
            } else {
                if ($user->hasErrors()) {
                    foreach ($user->getErrors() as $errors) {
                        foreach ($errors as $err) {
                            $error = $err;
                        }
                    }
                }

                if (empty($error)) {
                    $this->Flash->error(__('The user could not be saved. Please, try again.'));
                } else {
                    $this->Flash->error($error);
                }
            }
        }


        $this->set(compact('user'));
    }

    public function dashboard() {

    }

    /**
     * Reset Password  method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function resetPassword($forgotPasswordToken) {
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }

        $this->viewBuilder()->setLayout('home');

        $user = $this->Users->findByForgotPasswordToken($forgotPasswordToken)->first();
        if (!empty($user)) {
            $this->set('forgotPasswordToken', $forgotPasswordToken);
        } else {
            $this->Flash->error(__('Forgot password token has been expired. Please, try again.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }

    public function resetPasswordApi() {
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->findByForgotPasswordToken($this->request->data['forgot_password_token'])->first();
            if ($user) {
                /*
                 * Restrict user to edit only while listed fields
                 */
                $editableFields = ['password', 'verify_password', 'forgot_password_token'];
                foreach ($this->request->getData() as $field => $val) {
                    if (!in_array($field, $editableFields)) {
                        unset($this->request->getData()[$field]);
                    }
                }
                $user['forgot_password_token'] = "";
                $user = $this->Users->patchEntity($user, $this->request->getData());
                if ($this->Users->save($user)) {
                    $this->responseMessage = __('Your password has been updated.');
                    $this->responseCode = SUCCESS_CODE;
                } else {
                    $this->responseMessage = __('Something went wrong. Please, try again.');
                }
            } else {
                $this->responseMessage = __('Forgot password token has been expired. Please, try again.');
            }
        }
        echo $this->responseFormat();
        exit;
    }

    /**
     * Reset Password  method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function forgotPassword() {
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }
        $this->viewBuilder()->setLayout('home');
    }


    public function forgotPasswordApi() {
        $this->autoRender = false;
        $this->responseCode = CODE_BAD_REQUEST;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->findByEmail($this->request->getData('email'))->first();

            if (!empty($user)) {
                if ($user->active) {
                    $user->forgot_password_token = md5(uniqid(rand(), true));
                    $resetUrl = SITE_URL . 'users/reset-password/' . $user->forgot_password_token;
                    if ($this->Users->save($user)) {
                        $options = ['template' => 'forgot_password', 'to' => $this->request->getData('email'), 'subject' => 'Reset Password', 'viewVars' => ['name' => $user->first_name, 'resetUrl' => $resetUrl,]];

                        $this->loadComponent('EmailManager');
                        $this->EmailManager->sendEmail($options);
                        $this->responseCode = SUCCESS_CODE;
                        $this->responseMessage = __('A link to reset the password with the instruction has been sent to your inbox');
                    }
                } else {
                    $this->responseCode = EMAIL_DOESNOT_REGISTERED;
                    if ($user['registration_steps_done'] <= 2) {
                        $this->responseMessage = __('Your account has been submitted for review, we will contact you soon');
                    } else {
                        $this->responseMessage = __('Your account has been disabled by administrator, please send a message from "Contact Us" page.');
                    }
                }
            } else {
                $this->responseCode = EMAIL_DOESNOT_REGISTERED;
                $this->responseMessage = __('Email does not exists');
            }
        }

        echo $this->responseFormat();
        exit;
    }

    /**
     * View Profile method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function profile() {

        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Subscriptions', 'Images']]);

        $user['password'] = "";

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if (empty($data['password'])) {
                unset($data['password']);
            }


            $user = $this->Users->patchEntity($user, $data);

            if (empty($this->request->getData('password'))) {
                unset($user['password']);
            }


            if ($this->Users->save($user)) {
                $user = $this->Users->find('all')->contain(['Subscriptions', 'Images'])->where(['Users.id' => $user->id])->contain(['Images'])->first();
                $subscribed = count($user['subscriptions']) > 0 ? true : false;
                $user['subscribed'] = $subscribed;
                $this->Auth->setUser($user);
                $this->Flash->success(__('Your profile has been updated.'));

                return $this->redirect(['action' => 'profile']);
            } else {
                //show errors
            }


            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $this->set(compact('user'));
    }


    public function add() {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            die('okaokoka');
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }


    public function isUniqueEmail($id = null) {
        $this->autoRender = false;
        if ($id === null) {
            $email = $this->request->getQuery('email');
            if ($this->Users->findByEmail($email)->count()) {
                $alreadyExists = "false";
            } else {
                $alreadyExists = "true";
            }
        } else {
            $count = $this->Users->find()->where(['id !=' => $id, 'email' => $this->request->getQuery('email')])->count();
            if ($count) {
                $alreadyExists = "false";
            } else {
                $alreadyExists = "true";
            }
        }
        echo $alreadyExists;
        exit;
    }


    public function changePassword() {
        $user = $this->Users->get($this->Auth->user('id'), ['contain' => ['Subscriptions', 'Images']]);
        unset($user->password);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user->password = $this->getRequest()->getData('password');
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Your password has been updated.'));
            }

            return $this->redirect(['action' => 'dashboard']);
        }

        $this->set(compact('user'));
    }

    public function holidays() {

    }

    public function departments() {

    }

    public function home() {

    }

}
