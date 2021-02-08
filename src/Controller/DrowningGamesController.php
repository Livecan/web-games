<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * DrowningGame Controller
 *
 * @method \App\Model\Entity\DrowningGame[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DrowningGamesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * Start game method
     *
     * @param string|null $id Drowning Game id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function start($id)
    {
        $game = $this->DrowningGames->get($id, [
            'contain' => ['Users'],
        ]);
        $this->Authorization->authorize($game);
        if ($this->request->is(['post'])) {
            if ($this->DrowningGames->start($game)) {
                $this->Flash->success(__('The drowning game has been initialized.'));

                return $this->redirect(['action' => 'openBoard', $id = $id]);
            }
            $this->Flash->error(__('The drowning game could not be initialized. Please, try again.'));
        }
        $this->set(compact('game'));
    }
    
    public function openBoard($id) {
        $game = $this->DrowningGames->get($id, [
            'contain' => ['Users', 'DrTokens', 'DrTurns'],
        ]);
        $this->Authorization->authorize($game);
        
        $oceanBoard = $this->DrowningGames->getBoard($game);

        $this->set(compact('oceanBoard'));
    }
}
