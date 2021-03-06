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
            'contain' => ['GameStates', 'Users'],
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
        $this->Authorization->skipAuthorization();
        $game;
        if ($this->request->is('post')) {
            if ($game = $this->Games->addGame($this->request->getData(), $this->request->getAttribute('identity')->getOriginalData())) {
                $this->Flash->success(__('The game has been saved.'));

                return $this->redirect(['action' => 'waitingRoom', $game->id]);
            }
            $this->Flash->error(__('The game could not be saved. Please, try again.'));
        }
        
        $game = $this->Games->newEmptyEntity();
        $gameTypes = $this->Games->GameTypes->find('list')->select(['id', 'name']);
        $this->set(compact('game', 'gameTypes'));
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
            'contain' => ['Users'],
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
        $users = $this->Games->Users->find('list', ['limit' => 200]);
        $this->set(compact('game', 'users'));
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
        $game = $this->Games->get($id, [
            'contain' => ['Users']]);
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
        $games = $this->paginate($this->Games->find()->
                where([ 'game_state_id' => 1 ])->
                contain([ 'Creator', 'Users', 'GameTypes' ])->
                order(['created' => 'DESC']));
        $this->Authorization->skipAuthorization();
        $this->set(compact('games'));
    }
    
    /**
     * Join an existing game
     * 
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function waitingRoom($id)
    {
        $game = $this->Games->get($id, [
            'contain' => ['Users', 'GameStates', 'GameTypes'],
        ]);
        $this->Authorization->skipAuthorization();
        
        if ($this->request->is('post')) {
            if ($this->Games->addUser($game, $this->request->getAttribute('identity')->getOriginalData())) {
                $this->Flash->success(__('You joined the game.'));
            } else {
                $this->Flash->error(__('You can\'t join the game. Please, try again.'));
            }
        }
        $this->set(compact('game'));
    }
}
