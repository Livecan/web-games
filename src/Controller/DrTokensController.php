<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * DrTokens Controller
 *
 * @property \App\Model\Table\DrTokensTable $DrTokens
 * @method \App\Model\Entity\DrToken[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DrTokensController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $drTokens = $this->paginate($this->DrTokens);

        $this->set(compact('drTokens'));
    }

    /**
     * View method
     *
     * @param string|null $id Dr Token id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $drToken = $this->DrTokens->get($id, [
            'contain' => ['Games'],
        ]);

        $this->set(compact('drToken'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $drToken = $this->DrTokens->newEmptyEntity();
        if ($this->request->is('post')) {
            $drToken = $this->DrTokens->patchEntity($drToken, $this->request->getData());
            if ($this->DrTokens->save($drToken)) {
                $this->Flash->success(__('The dr token has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dr token could not be saved. Please, try again.'));
        }
        $games = $this->DrTokens->Games->find('list', ['limit' => 200]);
        $this->set(compact('drToken', 'games'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dr Token id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $drToken = $this->DrTokens->get($id, [
            'contain' => ['Games'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $drToken = $this->DrTokens->patchEntity($drToken, $this->request->getData());
            if ($this->DrTokens->save($drToken)) {
                $this->Flash->success(__('The dr token has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dr token could not be saved. Please, try again.'));
        }
        $games = $this->DrTokens->Games->find('list', ['limit' => 200]);
        $this->set(compact('drToken', 'games'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dr Token id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $drToken = $this->DrTokens->get($id);
        if ($this->DrTokens->delete($drToken)) {
            $this->Flash->success(__('The dr token has been deleted.'));
        } else {
            $this->Flash->error(__('The dr token could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
