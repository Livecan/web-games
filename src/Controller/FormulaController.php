<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\Time;

/**
 * Formula Controller
 *
 * @method \App\Model\Entity\Formula[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FormulaController extends AppController
{
    public function initialize(): void {
        parent::initialize();
        
        $this->Formula = new \App\Model\FormulaLogic\FormulaLogic();
        $this->FormulaGame = $this->loadModel('FormulaGames');
    }

    /**
     * Start method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful start, renders error otherwise.
     */
    public function start($id)
    {
        $formulaGame = $this->FormulaGames->get($id, ['contain' => ['FoGames', 'Users'],
            ]);
        $this->Authorization->authorize($formulaGame);
        if ($formulaGame->game_state_id == 1) {
            if ($this->request->is('post') && $formulaGame = $this->Formula->start($formulaGame, $this->request->getData())) {
                $this->Flash->success(__('The formula has been saved.'));
                return $this->redirect(['action' => 'getBoard', $id]);
            }
            $this->Flash->error(__('The formula game could not be started. Please, try again.'));
            return $this->redirect(['controller' => 'Games', 'action' => 'newGames']);
        }
        $this->Flash->error(__('The game has already started. Please, try again.'));
        return $this->redirect(['action' => 'getBoard', $id]);
    }
    
    public function getBoardUpdateJson($id)
    {
        $formulaGame = $this->FormulaGames->get($id, ['contain' => ['Users']]);
        $this->Authorization->authorize($formulaGame);
        $formulaBoard;
        if ($this->request->is('get')) {
            
            $modifiedDateQueryParam = $this->request->getQuery('modified');
            $modifiedDate = $modifiedDateQueryParam == null ? null : 
                    new Time($modifiedDateQueryParam);
            $formulaBoard = $this->Formula->getBoard($formulaGame,
                            $this->request->getAttribute("identity")->id,
                            $modifiedDate);
            if ($formulaBoard == null) {
                $this->Flash->error(__('Error occurred while retrieving board. Please, try again.'));
            }
        }
        $this->set(compact('formulaBoard'));
        $this->viewBuilder()->setOption('serialize', 'formulaBoard');
    }
    
    public function getBoard($id)
    {
        $formulaGame = $this->FormulaGames->get($id, ['contain' => ['Users', 'FoGames.FoTracks']]);
        
        $this->Authorization->authorize($formulaGame);
        if ($this->request->is('get') && $formulaGame->game_state_id != 1) {
            $this->set(compact('formulaGame'));
        } else {
            $this->Flash->error(__('Error occurred while retrieving board. Please, try again.'));
            $this->redirect(['controller' => 'Games', 'action' => 'newGames']);
        }
    }
    
    public function chooseMoveOption($id)
    {
        $formulaGame = $this->FormulaGames->get($id, ['contain' => ['FoCars']]);
        
        $this->Authorization->authorize($formulaGame);
        if ($this->request->is('post') && $formulaGame->game_state_id == 2) {
            $data = $this->request->getData();
            $this->Formula->chooseMoveOption($formulaGame, intval($data["move_option_id"]));
        } else {
            $this->Flash->error(__('Invalid operation.'));
        }
        
        $this->viewBuilder()->setOption('serialize', '');
    }
    
    public function chooseGear($id)
    {
        $formulaGame = $this->FormulaGame->get($id, ['contain' => ['FoCars']]);
        
        $this->Authorization->authorize($formulaGame);
        if ($this->request->is('post') && $formulaGame->game_state_id == 2) {
            $data = $this->request->getData();
            $this->Formula->chooseGear($formulaGame, intval($data["gear"]));
        } else {
            $this->Flash->error(__('Invalid operation.'));
        }
        
        $this->viewBuilder()->setOption('serialize', '');
    }
}
