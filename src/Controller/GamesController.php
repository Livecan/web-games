<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Games Controller
 *
 * @property \App\Model\Table\GamesTable $Games
 * @method \App\Model\Entity\Game[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GamesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $games = $this->paginate($this->Games);
        $this->Authorization->skipAuthorization();

        $this->set(compact('games'));
    }

    /**
     * View method
     *
     * @param string|null $id Game id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $game = $this->Games->get($id, [
            'contain' => ['GameStates','DrTokens', 'Users', 'DrTurns'],
        ]);
        $this->Authorization->skipAuthorization();

        $this->set(compact('game'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $game = $this->Games->newEmptyEntity();
        $this->Authorization->authorize($game);
        if ($this->request->is('post')) {
            $game = $this->Games->patchEntity($game, $this->request->getData());
            $loggedInUser = $this->getTableLocator()->get('Users')->find()
                    ->where(['id' => $this->request->getAttribute('identity')->getIdentifier()])
                    ->toList();
            $game->users = $loggedInUser;
            debug($game);
            if ($this->Games->save($game)) {
                $this->Flash->success(__('The game has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The game could not be saved. Please, try again.'));
        }
        $drTokens = $this->Games->DrTokens->find('list', ['limit' => 200]);
        $users = $this->Games->Users->find('list', ['limit' => 200]);
        $this->set(compact('game', 'drTokens', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Game id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $game = $this->Games->get($id, [
            'contain' => ['DrTokens', 'Users'],
        ]);
        $this->Authorization->authorize($game);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $game = $this->Games->patchEntity($game, $this->request->getData());
            if ($this->Games->save($game)) {
                $this->Flash->success(__('The game has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The game could not be saved. Please, try again.'));
        }
        $drTokens = $this->Games->DrTokens->find('list', ['limit' => 200]);
        $users = $this->Games->Users->find('list', ['limit' => 200]);
        $this->set(compact('game', 'drTokens', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Game id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $game = $this->Games->get($id);
        $this->Authorization->authorize($game);
        if ($this->Games->delete($game)) {
            $this->Flash->success(__('The game has been deleted.'));
        } else {
            $this->Flash->error(__('The game could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * List new games
     * 
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function newGames()
    {
        $games = $this->paginate($this->Games->find()->where([ 'game_state_id' => 1 ])->contain([ 'Users' ]));
        $this->Authorization->skipAuthorization();
//debug($games);
        $this->set(compact('games'));
    }
}
