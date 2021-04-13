<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\Time;
use App\Model\FormulaLogic\FormulaLogic;
use App\Model\FormulaLogic\FormulaSetupLogic;
use App\Model\Entity\FoDamage;

/**
 * Formula Controller
 *
 * @method \App\Model\Entity\Formula[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FormulaController extends AppController
{
    public function initialize(): void {
        parent::initialize();
        
        $this->Formula = new FormulaLogic();
        $this->FormulaSetup = new FormulaSetupLogic();
        $this->FormulaGames = $this->loadModel('FormulaGames');
        $this->FoDamages = $this->loadModel('FoDamages');
    }
    
    public function index()
    {
        $formulaGames = $this->paginate(
                $this->FormulaGames->find('all')->
                where(['game_type_id' => 2])->
                contain(['FoGames'])->
                order(['FormulaGames.created' => 'DESC']));
        $this->Authorization->skipAuthorization();

        $this->set(compact('formulaGames'));
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
        $formulaGame = $this->FormulaGames->get($id, ['contain' => ['FoCars']]);
        
        $this->Authorization->authorize($formulaGame);
        if ($this->request->is('post') && $formulaGame->game_state_id == 2) {
            $data = $this->request->getData();
            $this->Formula->chooseGear($formulaGame, intval($data["gear"]));
        } else {
            $this->Flash->error(__('Invalid operation.'));
        }
        
        $this->viewBuilder()->setOption('serialize', '');
    }
    
    public function createNewGame() {
        $this->Authorization->skipAuthorization();
        if ($this->request->is('post')) {
            if ($formulaGame = $this->FormulaSetup->createNewGame(
                    $this->request->getAttribute('identity')->getOriginalData())) {
                return $this->redirect(['action' => 'getWaitingRoom', $formulaGame->id]);
            }
            $this->Flash->error(__('The game could not be saved. Please, try again.'));
        }
    }
    
    public function getWaitingRoom($id) {
        $formulaGame = $this->FormulaGames->get($id, ['contain' => ['Users']]);
        $this->Authorization->authorize($formulaGame);
        if ($this->request->is('get')) {
            $this->set(compact('formulaGame'));
        } else {
            $this->Flash->error(__('Error occurred while retrieving board. Please, try again.'));
            $this->redirect(['controller' => 'Games', 'action' => 'newGames']);
        }
    }
    
    public function getSetupUpdateJson($id) {
        $formulaGame = $this->FormulaGames->get($id,
                ['contain' => ['Users']]);
        $this->Authorization->authorize($formulaGame);
        
        if ($this->request->is('get') && $formulaGame->game_state_id == 1) {
            
            $modifiedDateQueryParam = $this->request->getQuery('modified-setup');
            $modifiedDate = $modifiedDateQueryParam == null ? null : 
                    new Time($modifiedDateQueryParam);
            $formulaGame = $this->FormulaSetup->getSetupUpdateJson($formulaGame,
                    $this->request->getAttribute('identity')->getOriginalData(),
                    $modifiedDate);
            $this->set(compact('formulaGame'));
            $this->viewBuilder()->setOption('serialize', 'formulaGame');
        } else {
            $this->Flash->error(__('Error occurred while retrieving board. Please, try again.'));
            $this->redirect(['controller' => 'Games', 'action' => 'newGames']);
        }   
    }
    
    public function editSetup($id) {
        $formulaGame = $this->FormulaGames->get($id,
                ['contain' => ['Users', 'FoGames']]);
        $this->Authorization->authorize($formulaGame);
        
        if ($this->request->is('post') && $formulaGame->game_state_id == 1) {
            $this->FormulaSetup->editSetup($formulaGame, $this->request->getData());
            $this->viewBuilder()->setOption('serialize', '');
        }
    }
    
    public function editDamage($id) {
        $damageId = $this->request->getData('damage_id');
        $wearPoints = intval($this->request->getData('wear_points'));
        $foDamage = $this->FoDamages->get($damageId, ['contain' => ['FoCars']]);
        $formulaGame = $this->FormulaGames->get($id);
        $this->Authorization->authorize($foDamage);
        if ($this->request->is('post') && $formulaGame->game_state_id == 1) {
            $this->FormulaSetup->editDamage($formulaGame, $foDamage, $wearPoints);
            $this->viewBuilder()->setOption('serialize', '');
        }
    }
    
    public function joinGame($id) {
        $formulaGame = $this->FormulaGames->get($id, ['contain' => ['Users', 'FoGames']]);
        $this->Authorization->skipAuthorization();
        if ($this->request->is('post')) {
            $this->FormulaSetup->joinGame($formulaGame,
                    $this->request->getAttribute('identity')->getOriginalData());
            $this->redirect(['action' => 'getWaitingRoom', $id]);
        }
    }
}
