<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Formula Controller
 *
 * @method \App\Model\Entity\Formula[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FormulaController extends AppController
{
    public function initialize(): void {
        parent::initialize();
        
        $this->Formula = new \App\Model\Logic\FormulaLogic();
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
        if ($this->request->is('post')) {
            if ($formulaGame = $this->Formula->start($formulaGame, $this->request->getData())) {
                $this->Flash->success(__('The formula has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The formula could not be saved. Please, try again.'));
        }
        $this->set(compact('formula'));
    }
}
