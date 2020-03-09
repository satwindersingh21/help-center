<?php

namespace App\Controller\Admin;

use Cake\Controller\Controller;
use Cake\Event\Event;

class AppController extends Controller {
    
    public $responseCode = SUCCESS_CODE;
    public $responseMessage = "";
    public $responseData = [];
    public $currentPage = 0;
    public $authUserId = 0;
    
    public function initialize() {
        
        parent::initialize();
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie');
        $this->loadComponent('Csrf');
        
        $this->loadComponent('Auth', [
            'storage' => 'Session',
            'authenticate' => [
                'Form' => [
                    'userModel' => 'Admins',
                    'fields' => ['username' => 'email', 'password' => 'password']
                ],
            ],
            'loginAction' => ['controller' => 'Admins', 'action' => 'login'],
            'loginRedirect' => ['controller' => 'Users', 'action' => 'index']
        ]);
        
        if ($this->Auth->user()) {
            $this->set('authUser', $this->Auth->user());
            $this->searchConditions();
            $this->viewBuilder()->setLayout('admin');
        }
        
        
    }
    
    public function beforeFilter(Event $event) {
        $this->getEventManager()->off($this->Csrf);
        $user = $this->Auth->user();
        if ($user) {
            if ($this->request->getParam('prefix') == 'admin') {
                if ($user['role'] != "Admin") {
                    return $this->redirect(SITE_URL);
                }
            }
        }
        
    }
    
    
    function encryptpass($password, $method = 'md5', $crop = true, $start = 4, $end = 10) {
        if ($crop) {
            $password = $method(substr($method($password), $start, $end));
        } else {
            $password = $method($password);
        }
        return $password;
    }
    
    public function responseFormat() {
        $returnArray = [
            "code" => $this->responseCode,
            "message" => $this->responseMessage,
        ];
        if ($this->currentPage > 0) {
            $this->responseData['currentPage'] = $this->currentPage;
        }
        
        if (isset($this->responseData['total'])) {
            $this->responseData['pages'] = ceil($this->responseData['total'] / PAGE_LIMIT);
        }
        
        $returnArray['data'] = !empty($this->responseData) ? $this->responseData : ['message' => 'Data not found'];
        
        return json_encode($returnArray);
    }
    
    public function getErrorMessage($errors, $show = false, $field = []) {
        if (is_array($errors)) {
            foreach ($errors as $key => $error) {
                $field[$key] = "[" . $key . "]";
                $this->getErrorMessage($error, $show, $field);
                break;
            }
        } else {
            $this->responseMessage = ($show) ? implode(" >> ", $field) . " >> " . $errors : $errors;
        }
    }
    
        public function getCurrentPage() {
        $this->currentPage = empty($this->request->getQuery('page')) ? 1 : $this->request->getQuery('page');
        return $this->currentPage;
    }
    
    public function getOption($name) {
        $this->loadModel('Options');
        
        $option = $this->Options->find('all')->where(['option_name' => $name])->first();
        
        return (empty($option)) ? "Not Found" : (empty($option->option_value)) ? $option->default_value : $option->option_value;
    }
    
    public function searchConditions() {
        if ($this->request->is('post') && !empty($this->request->getData('search_in_listings'))) {
            $keyString = strtolower($this->request->getData('key'));
            $matches = explode(",", $this->request->getData('match'));
            $conditions = [];
            foreach ($matches as $match) {
                $conditions['OR']["LOWER(" . $match . ") LIKE"] = "%$keyString%";
            }
            $this->paginate = [
                'conditions' => $conditions
            ];
            
            $this->set('search_key', $keyString);
        }
    }
    
    public function getPages(){
        $this->loadModel('Pages');
        return $this->Pages->find('all')->select(['id', 'page'])->all();
    }
    
}
