<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * DrTurns Model
 *
 * @property \App\Model\Table\GamesTable&\Cake\ORM\Association\BelongsTo $Games
 *
 * @method \App\Model\Entity\DrTurn newEmptyEntity()
 * @method \App\Model\Entity\DrTurn newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DrTurn[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DrTurn get($primaryKey, $options = [])
 * @method \App\Model\Entity\DrTurn findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DrTurn patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DrTurn[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DrTurn|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DrTurn saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DrTurn[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrTurn[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrTurn[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrTurn[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DrTurnsTable extends Table
{
    use LocatorAwareTrait;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        
        $this->addBehavior('Timestamp');

        $this->setTable('dr_turns');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Games', [
            'foreignKey' => 'game_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        
        $this->gamesUsers = $this->getTableLocator()->get('GamesUsers');
        $this->drTokensGames = $this->getTableLocator()->get('DrTokensGames');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('position')
            ->requirePresence('position', 'create')
            ->notEmptyString('position');

        $validator
            ->integer('round')
            ->requirePresence('round', 'create')
            ->notEmptyString('round');

        $validator
            ->integer('roll')
            ->requirePresence('roll', 'create')
            ->notEmptyString('roll');

        $validator
            ->boolean('returning')
            ->requirePresence('returning', 'create')
            ->notEmptyString('returning');

        $validator
            ->boolean('taking')
            ->requirePresence('taking', 'create')
            ->notEmptyString('taking');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['game_id'], 'Games'), ['errorField' => 'game_id']);
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
    
    public function getPositionPlayer($game_id) {
        $playerCount = $this->gamesUsers->find()->where(['game_id' => $game_id])->count();
        $positionPlayers = $this->find('all', ['order' => ['created' => 'DESC']])->
                contain(['Users' =>
                    ['Games' =>
                        function (Query $q) use ($game_id) {
                            return $q->select(['GamesUsers.order_number'])->where(['game_id' => $game_id]);
                        }
                    ]
                ])->
                select(['position'])->
                select($this->Users)->
                where(['game_id' => $game_id])->
                limit($playerCount)->
                all()->
                toArray();
        foreach ($positionPlayers as $positionPlayer) {
            $positionPlayer->user->order_number = $positionPlayer->user->games[0]->_joinData->order_number;
            $positionPlayer->unset('games');
        }
        return $positionPlayers;
    }
    
    public function getOxygenLevel($game_id) {
        return $this->find('all', ['order' => ['created' => 'DESC']])->
                select('oxygen')->
                where(['game_id' => $game_id])->
                first()->
                oxygen;
    }
    
    public function getCurrentTurnUser($game_id) {
        return $this->find('all', ['order' => ['created' => 'DESC']])->
                contain(['Users'])->
                select($this->Users)->
                where(['game_id' => $game_id])->
                first()->user;
    }
    
    public function getLastTurn($game_id) {
        return $this->find('all', ['order' => ['created' => 'DESC']])->
            where(['game_id' => $game_id])->
            first();
    }
    
    public function canTakeTreasure($board) {
        return $board->depths[$board->last_turn->position]->tokens;
    }
    
    public function processActions($board, $data, $user) {
        
        if (array_key_exists('start_returning', $data)) {
            $board->last_turn->returning |= $data['start_returning'];
        }
        
        if ($this->canTakeTreasure($board) && array_key_exists('taking', $data) && $data['taking']) {
            $gameTokens = $this->drTokensGames->find('all')->
                where(['game_id' => $board->id, 'position' => $board->last_turn->position])->
                toArray();
            foreach ($gameTokens as $gameToken) {
                $gameToken->group_number = $board->last_turn->id;
                $gameToken->user_id = $user->id;
                $gameToken->position = null;
                $gameToken->id_token_state = 2;
            }
            $this->drTokensGames->saveMany($gameTokens);
        }
        
        $this->processTurns($board, array_key_exists('finish', $data) && $data['finish']);
        
        return true;    //TODO: don't forget to save the actions!
    }
    
    private function processTurns($board, $finished) {
        if ($finished || ($board->last_turn->returning && $board->last_turn->taking)) {  //TOD: add impossible to drop or dropped already
            $nextUser = $this->gamesUsers->getNextUser($board->id, $board->last_turn->user_id);
            $roll = [rand(1, 3), rand(1, 3)];
            $userTakenTreasuresCount = $this->drTokensGames->getUserTakenTreasuresCount($board->id, $nextUser->id);
            $moveCount = array_sum($roll) - $userTakenTreasuresCount;
            //TODO: now based on whether player started to return already or not (get from db last turn for the player), move would go up or down
            //TODO: process turns until User action required
        }
    }
}
