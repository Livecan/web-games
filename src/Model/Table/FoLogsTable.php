<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Entity\FoDamage;
use App\Model\Entity\FoCar;
use App\Model\Entity\FoLog;

/**
 * FoLogs Model
 *
 * @property \App\Model\Table\FoCarsTable&\Cake\ORM\Association\BelongsTo $FoCars
 * @property \App\Model\Table\FoPositionsTable&\Cake\ORM\Association\BelongsTo $FoPositions
 * @property \App\Model\Table\FoDamagesTable&\Cake\ORM\Association\HasMany $FoDamages
 *
 * @method \App\Model\Entity\FoLog newEmptyEntity()
 * @method \App\Model\Entity\FoLog newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FoLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FoLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\FoLog findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FoLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FoLog[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FoLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoLog[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoLog[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoLog[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoLog[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FoLogsTable extends Table
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

        $this->setTable('fo_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('FoCars', [
            'foreignKey' => 'fo_car_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('FoPositions', [
            'foreignKey' => 'fo_position_id',
        ]);
        $this->hasMany('FoDamages', [
            'foreignKey' => 'fo_log_id',
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
            ->allowEmptyString('gear');

        $validator
            ->integer('roll')
            ->allowEmptyString('roll');

        $validator
            ->integer('ranking')
            ->allowEmptyString('ranking');

        $validator
            ->scalar('type')
            ->maxLength('type', 1)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

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
        $rules->add($rules->existsIn(['fo_car_id'], 'FoCars'), ['errorField' => 'fo_car_id']);
        $rules->add($rules->existsIn(['fo_position_id'], 'FoPositions'), ['errorField' => 'fo_position_id']);

        return $rules;
    }
    
    public function logGameStart($formulaCars) {
        collection($formulaCars)->each(function(FoCar $formulaCar) {
            $logDamages = collection($formulaCar->fo_damages)->
                    map(function(FoDamage $damage) {
                        return new FoDamage([
                            'fo_e_damage_type_id' => $damage->fo_e_damage_type_id,
                            'wear_points' => $damage->wear_points,
                        ]);
                })->toList();
            $foLog = new FoLog(['fo_car_id' => $formulaCar->id,
                        'fo_position_id' => $formulaCar->fo_position_id,
                        'gear' => $formulaCar->gear,
                        'type' => FoLog::TYPE_INITIAL,
                        'fo_damages' => $logDamages,
                ]);
            $foLog = $this->save($foLog, ['associated' => ['FoDamages']]);
        });
    }
    
    public function logFirstMove(FoCar $foCar) {
        return $this->save(new FoLog([
            'fo_car_id' => $foCar->id,
            'gear' => $foCar->gear + 1,
            'type' => FoLog::TYPE_MOVE,
        ]));
    }
    
    public function logRoll(FoCar $foCar, $roll, $logType) {
        if ($logType == FoLog::TYPE_INITIAL) {
            $foLog = $this->find('all')->
                    where(['fo_car_id' => $foCar->id, 'type' => FoLog::TYPE_INITIAL])->
                    toList()[0];
            $this->patchEntity($foLog, ['roll' => $roll]);
            return $this->save($foLog);
        } else {
            return $this->save(new FoLog(['fo_car_id' => $foCar->id,
                'gear' => $foCar->gear,
                'roll' => $roll,
                'type' => $logType,
            ]));
        }
    }
}
