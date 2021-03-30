<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FoCars Model
 *
 * @property \App\Model\Table\GamesTable&\Cake\ORM\Association\BelongsTo $Games
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\FoPositionsTable&\Cake\ORM\Association\BelongsTo $FoPositions
 * @property \App\Model\Table\FoDamagesTable&\Cake\ORM\Association\HasMany $FoDamages
 * @property \App\Model\Table\FoLogsTable&\Cake\ORM\Association\HasMany $FoLogs
 * @property \App\Model\Table\FoMoveOptionsTable&\Cake\ORM\Association\HasMany $FoMoveOptions
 *
 * @method \App\Model\Entity\FoCar newEmptyEntity()
 * @method \App\Model\Entity\FoCar newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FoCar[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FoCar get($primaryKey, $options = [])
 * @method \App\Model\Entity\FoCar findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FoCar patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FoCar[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FoCar|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoCar saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoCar[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoCar[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoCar[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoCar[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FoCarsTable extends Table
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

        $this->setTable('fo_cars');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Games', [
            'foreignKey' => 'game_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('FoPositions', [
            'foreignKey' => 'fo_position_id',
        ]);
        $this->hasMany('FoDamages', [
            'foreignKey' => 'fo_car_id',
        ]);
        $this->hasMany('FoLogs', [
            'foreignKey' => 'fo_car_id',
        ]);
        $this->hasMany('FoMoveOptions', [
            'foreignKey' => 'fo_car_id',
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
            ->integer('gear')
            ->notEmptyString('gear');

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
        $rules->add($rules->existsIn(['fo_position_id'], 'FoPositions'), ['errorField' => 'fo_position_id']);

        return $rules;
    }
    
    public function createUserCar($game_id, $user_id, $damages, $save = true) {
        $foCar = $this->newEntity(['game_id' => $game_id,
            'user_id' => $user_id,
            ]);
        $foCar->fo_damages = $damages;
        
        if ($save) {
            return $this->save($foCar, ['associated' => ['FoDamages']]);
        } else {
            return $foCar;
        }
    }
    
    public function generateCarOrder($game_id) {
        $foCars = $this->find('all')->
                contain(['FoDamages' => function(Query $q) {
                    return $q->select($this->FoDamages);
                }, 'FoPositions' => function(Query $q) {
                    return $q->select($this->FoPositions);
                }])->
                select($this)->
                where(['game_id' => $game_id])->
                order(['lap' => 'DESC', 'FoPositions.order' => 'DESC']);
        $order = 1;
        foreach ($foCars as $foCar) {
            $foCar->order = $order++;
        }
        return $this->saveMany($foCars);
    }
}