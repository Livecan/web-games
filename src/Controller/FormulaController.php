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
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        //$formula = $this->paginate($this->Formula);

        $this->set(compact('formula'));
    }

    /**
     * View method
     *
     * @param string|null $id Formula id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $formula = $this->Formula->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('formula'));
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

    /**
     * Edit method
     *
     * @param string|null $id Formula id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $formula = $this->Formula->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $formula = $this->Formula->patchEntity($formula, $this->request->getData());
            if ($this->Formula->save($formula)) {
                $this->Flash->success(__('The formula has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The formula could not be saved. Please, try again.'));
        }
        $this->set(compact('formula'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Formula id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $formula = $this->Formula->get($id);
        if ($this->Formula->delete($formula)) {
            $this->Flash->success(__('The formula has been deleted.'));
        } else {
            $this->Flash->error(__('The formula could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
