<?php

namespace App\Controller\Admin;


/**
 * Complaints Controller
 *
 * @property \App\Model\Table\ComplaintsTable $Complaints
 *
 * @method \App\Model\Entity\Complaint[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ComplaintsController extends AppController {
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index() {
        $this->paginate['contain'] = ['Users'];
        $complaints = $this->paginate($this->Complaints);

        $this->set(compact('complaints'));
    }

    /**
     * View method
     *
     * @param string|null $id Complaint id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $complaint = $this->Complaints->get($id, ['contain' => ['Users'],]);

        $this->set('complaint', $complaint);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $complaint = $this->Complaints->newEntity();
        if ($this->request->is('post')) {
            $complaint = $this->Complaints->patchEntity($complaint, $this->request->getData());

            if ($this->Complaints->save($complaint)) {
                $this->Flash->success(__('The complaint has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The complaint could not be saved. Please, try again.'));
        }
        $users = $this->Complaints->Users->find('list', ['limit' => 200]);
        $this->set(compact('complaint', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Complaint id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $complaint = $this->Complaints->get($id, ['contain' => [],]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $complaint = $this->Complaints->patchEntity($complaint, $this->request->getData());
            if ($this->Complaints->save($complaint)) {
                $this->Flash->success(__('The complaint has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The complaint could not be saved. Please, try again.'));
        }
        $users = $this->Complaints->Users->find('list', ['limit' => 200]);
        $this->set(compact('complaint', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Complaint id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $complaint = $this->Complaints->get($id);
        if ($this->Complaints->delete($complaint)) {
            $this->Flash->success(__('The complaint has been deleted.'));
        } else {
            $this->Flash->error(__('The complaint could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
