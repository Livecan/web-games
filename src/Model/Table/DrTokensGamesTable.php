<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DrTokensGames Model
 *
 * @property \App\Model\Table\GamesTable&\Cake\ORM\Association\BelongsTo $Games
 * @property \App\Model\Table\DrTokensTable&\Cake\ORM\Association\BelongsTo $DrTokens
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\DrTokensGame newEmptyEntity()
 * @method \App\Model\Entity\DrTokensGame newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DrTokensGame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DrTokensGame get($primaryKey, $options = [])
 * @method \App\Model\Entity\DrTokensGame findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DrTokensGame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DrTokensGame[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DrTokensGame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DrTokensGame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DrTokensGame[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrTokensGame[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrTokensGame[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrTokensGame[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DrTokensGamesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('dr_tokens_games');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Games', [
            'foreignKey' => 'game_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('DrTokens', [
            'foreignKey' => 'dr_token_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
        $this->belongsTo('DrTokenStates', [
            'foreignKey' => 'dr_token_state_id',
        ]);
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
            ->allowEmptyString('position');

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
        $rules->add($rules->existsIn(['dr_token_id'], 'DrTokens'), ['errorField' => 'dr_token_id']);
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
    
    public function getTokensByPosition($game_id) {
        $results = $this->find('all', ['order' => ['position' => 'ASC']])->
                contain(['DrTokens'])->
                select('position')->
                select("DrTokens.type")->
                where(['game_id' => $game_id])->
                all()->
                toArray();
        $tokensByPosition = [];
        foreach ($results as $result) {
            if (!array_key_exists($result->position, $tokensByPosition)) {
                $tokensByPosition[$result->position] = [];
            }
            $tokensByPosition[$result->position][] = $result->dr_token;
        }
        return $tokensByPosition;
    }
    
    public function getUserTreasures($game_id, $user_id, $dr_token_state_id) {
        return $this->find('all')->
                contain(['DrTokens'])->
                where(['game_id' => $game_id, 'user_id' => $user_id, 'dr_token_state_id' => $dr_token_state_id])->
                toArray();
    }
    
    public function getUserTakenTreasuresCount($game_id, $user_id) {
        $query = $this->find('all');
        return $query->
                where(['game_id' => $game_id, 'user_id' => $user_id, 'dr_token_state_id' => 2])->
                select($query->func()->min('id'))->
                group('group_number')->
                count();
        
    }
    
    public function getPlayersTokens($game_id) {
        $playersTokensResult = $this->find('all')->
                contain(['DrTokens'])->
                select(['user_id', 'group_number', 'dr_token_state_id'])->
                select($this->DrTokens)->
                where(['game_id' => $game_id])->
                whereInList('dr_token_state_id', [2, 3])->
                toList();
        return collection($playersTokensResult)->
                groupBy('user_id')->
                map(function($userTokens) {
                    return collection($userTokens)->groupBy('dr_token_state_id')->
                            map(function ($userStateTokens) {
                                return collection($userStateTokens)->
                                        groupBy('group_number')->
                                        map(function($group) {
                                            return collection($group)->
                                                    extract('dr_token')->
                                                    toList();
                                        })->
                                        toArray();
                            })->
                            toArray();
                })->
                toList();
    }
    
    public function claimPlayersTokens($game_id, $user_ids) {
        $this->query()->update()->
                set('dr_token_state_id', 3)->
                where(['game_id' => $game_id])->
                whereInList('user_id', $user_ids)->
                execute();
    }
    
    public function shiftTokens($game_id, $starting_position) {
        $tokensToShift = $this->find('all')->
                where(['game_id' => $game_id, 'position >' => $starting_position])->
                toArray();
        foreach ($tokensToShift as $_tokenToShift) {
            $_tokenToShift->position--;
        }
        $this->saveMany($tokensToShift);
    }
    
    public function placeDroppedTokens($game_id, $position, $tokenGroup) {
        $droppedTokensPositions = $this->find('all')->
                where(['game_id' => $game_id])->
                whereInList('dr_token_id', $tokenGroup)->
                toArray();
        foreach($droppedTokensPositions as $droppedTokenPosition) {
            $droppedTokenPosition->position = $position;
            $droppedTokenPosition->user_id = null;
            $droppedTokenPosition->group_number = $tokenGroup[0];
            $droppedTokenPosition->dr_token_state_id = 1;
        }
        $this->saveMany($droppedTokensPositions);
    }
}
