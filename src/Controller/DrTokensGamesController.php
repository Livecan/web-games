<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * DrTokensGames Controller
 *
 * @property \App\Model\Table\DrTokensGamesTable $DrTokensGames
 * @method \App\Model\Entity\DrTokensGame[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DrTokensGamesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Games', 'DrTokens', 'Users'],
        ];
        $drTokensGames = $this->paginate($this->DrTokensGames);

        $this->set(compact('drTokensGames'));
    }

    /**
     * View method
     *
     * @param string|null $id Dr Tokens Game id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $drTokensGame = $this->DrTokensGames->get($id, [
            'contain' => ['Games', 'DrTokens', 'Users'],
        ]);

        $this->set(compact('drTokensGame'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $drTokensGame = $this->DrTokensGames->newEmptyEntity();
        if ($this->request->is('post')) {
            $drTokensGame = $this->DrTokensGames->patchEntity($drTokensGame, $this->request->getData());
            if ($this->DrTokensGames->save($drTokensGame)) {
                $this->Flash->success(__('The dr tokens game has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dr tokens game could not be saved. Please, try again.'));
        }
        $games = $this->DrTokensGames->Games->find('list', ['limit' => 200]);
        $drTokens = $this->DrTokensGames->DrTokens->find('list', ['limit' => 200]);
        $users = $this->DrTokensGames->Users->find('list', ['limit' => 200]);
        $this->set(compact('drTokensGame', 'games', 'drTokens', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dr Tokens Game id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $drTokensGame = $this->DrTokensGames->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $drTokensGame = $this->DrTokensGames->patchEntity($drTokensGame, $this->request->getData());
            if ($this->DrTokensGames->save($drTokensGame)) {
                $this->Flash->success(__('The dr tokens game has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dr tokens game could not be saved. Please, try again.'));
        }
        $games = $this->DrTokensGames->Games->find('list', ['limit' => 200]);
        $drTokens = $this->DrTokensGames->DrTokens->find('list', ['limit' => 200]);
        $users = $this->DrTokensGames->Users->find('list', ['limit' => 200]);
        $this->set(compact('drTokensGame', 'games', 'drTokens', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dr Tokens Game id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $drTokensGame = $this->DrTokensGames->get($id);
        if ($this->DrTokensGames->delete($drTokensGame)) {
            $this->Flash->success(__('The dr tokens game has been deleted.'));
        } else {
            $this->Flash->error(__('The dr tokens game could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
