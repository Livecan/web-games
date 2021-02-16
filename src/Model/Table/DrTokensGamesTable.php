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
                select($this->DrTokens)->
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
    
    public function getUserTreasures($game_id, $user_id, $token_state_id) {
        return $this->find('all')->
                contain(['DrTokens'])->
                where(['game_id' => $game_id, 'user_id' => $user_id, 'token_state_id' => $token_state_id])->
                toArray();
    }
    
    public function getUserTakenTreasuresCount($game_id, $user_id) {
        return $this->find('all')->
                where(['game_id' => $game_id, 'user_id' => $user_id, 'token_state_id' => 2])->
                group('group_number')->
                count();
    }
}
