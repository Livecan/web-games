<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * GamesUsers Controller
 *
 * @property \App\Model\Table\GamesUsersTable $GamesUsers
 * @method \App\Model\Entity\GamesUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GamesUsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Games', 'Users'],
        ];
        $gamesUsers = $this->paginate($this->GamesUsers);

        $this->set(compact('gamesUsers'));
    }

    /**
     * View method
     *
     * @param string|null $id Games User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $gamesUser = $this->GamesUsers->get($id, [
            'contain' => ['Games', 'Users'],
        ]);

        $this->set(compact('gamesUser'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $gamesUser = $this->GamesUsers->newEmptyEntity();
        if ($this->request->is('post')) {
            $gamesUser = $this->GamesUsers->patchEntity($gamesUser, $this->request->getData());
            if ($this->GamesUsers->save($gamesUser)) {
                $this->Flash->success(__('The games user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The games user could not be saved. Please, try again.'));
        }
        $games = $this->GamesUsers->Games->find('list', ['limit' => 200]);
        $users = $this->GamesUsers->Users->find('list', ['limit' => 200]);
        $this->set(compact('gamesUser', 'games', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Games User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $gamesUser = $this->GamesUsers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $gamesUser = $this->GamesUsers->patchEntity($gamesUser, $this->request->getData());
            if ($this->GamesUsers->save($gamesUser)) {
                $this->Flash->success(__('The games user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The games user could not be saved. Please, try again.'));
        }
        $games = $this->GamesUsers->Games->find('list', ['limit' => 200]);
        $users = $this->GamesUsers->Users->find('list', ['limit' => 200]);
        $this->set(compact('gamesUser', 'games', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Games User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $gamesUser = $this->GamesUsers->get($id);
        if ($this->GamesUsers->delete($gamesUser)) {
            $this->Flash->success(__('The games user has been deleted.'));
        } else {
            $this->Flash->error(__('The games user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
