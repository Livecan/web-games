<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * DrTurns Controller
 *
 * @property \App\Model\Table\DrTurnsTable $DrTurns
 * @method \App\Model\Entity\DrTurn[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DrTurnsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Games', 'Players'],
        ];
        $drTurns = $this->paginate($this->DrTurns);

        $this->set(compact('drTurns'));
    }

    /**
     * View method
     *
     * @param string|null $id Dr Turn id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $drTurn = $this->DrTurns->get($id, [
            'contain' => ['Games', 'Players'],
        ]);

        $this->set(compact('drTurn'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $drTurn = $this->DrTurns->newEmptyEntity();
        if ($this->request->is('post')) {
            $drTurn = $this->DrTurns->patchEntity($drTurn, $this->request->getData());
            if ($this->DrTurns->save($drTurn)) {
                $this->Flash->success(__('The dr turn has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dr turn could not be saved. Please, try again.'));
        }
        $games = $this->DrTurns->Games->find('list', ['limit' => 200]);
        $players = $this->DrTurns->Players->find('list', ['limit' => 200]);
        $this->set(compact('drTurn', 'games', 'players'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dr Turn id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $drTurn = $this->DrTurns->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $drTurn = $this->DrTurns->patchEntity($drTurn, $this->request->getData());
            if ($this->DrTurns->save($drTurn)) {
                $this->Flash->success(__('The dr turn has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dr turn could not be saved. Please, try again.'));
        }
        $games = $this->DrTurns->Games->find('list', ['limit' => 200]);
        $players = $this->DrTurns->Players->find('list', ['limit' => 200]);
        $this->set(compact('drTurn', 'games', 'players'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dr Turn id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $drTurn = $this->DrTurns->get($id);
        if ($this->DrTurns->delete($drTurn)) {
            $this->Flash->success(__('The dr turn has been deleted.'));
        } else {
            $this->Flash->error(__('The dr turn could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
