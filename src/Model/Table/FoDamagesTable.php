<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\FormulaLogic\DiceLogic;
use App\Model\Entity\FoDamage;
use App\Model\Entity\FoLog;
use App\Model\Entity\FoCar;
use Cake\Collection\CollectionInterface;

/**
 * FoDamages Model
 *
 * @property \App\Model\Table\FoCarsTable&\Cake\ORM\Association\BelongsTo $FoCars
 * @property \App\Model\Table\FoMoveOptionsTable&\Cake\ORM\Association\BelongsTo $FoMoveOptions
 * @property \App\Model\Table\FoLogsTable&\Cake\ORM\Association\BelongsTo $FoLogs
 * @property \App\Model\Table\FoEDamageTypesTable&\Cake\ORM\Association\BelongsTo $FoEDamageTypes
 *
 * @method \App\Model\Entity\FoDamage newEmptyEntity()
 * @method \App\Model\Entity\FoDamage newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FoDamage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FoDamage get($primaryKey, $options = [])
 * @method \App\Model\Entity\FoDamage findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FoDamage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FoDamage[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FoDamage|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoDamage saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoDamage[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoDamage[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoDamage[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoDamage[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FoDamagesTable extends Table {
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

        $this->setTable('fo_damages');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('FoCars', [
            'foreignKey' => 'fo_car_id',
        ]);
        $this->belongsTo('FoMoveOptions', [
            'foreignKey' => 'fo_move_option_id',
        ]);
        $this->belongsTo('FoLogs', [
            'foreignKey' => 'fo_log_id',
        ]);
        $this->belongsTo('FoEDamageTypes', [
            'foreignKey' => 'fo_e_damage_type_id',
            'joinType' => 'INNER',
        ]);
        
        $this->FoLogs = $this->getTableLocator()->get('FoLogs');
        $this->DiceLogic = new DiceLogic();
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
            ->integer('wear_points')
            ->requirePresence('wear_points', 'create')
            ->notEmptyString('wear_points');

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
        $rules->add($rules->existsIn(['fo_move_option_id'], 'FoMoveOptions'), ['errorField' => 'fo_move_option_id']);
        $rules->add($rules->existsIn(['fo_log_id'], 'FoLogs'), ['errorField' => 'fo_log_id']);
        $rules->add($rules->existsIn(['fo_e_damage_type_id'], 'FoEDamageTypes'), ['errorField' => 'fo_e_damage_type_id']);

        return $rules;
    }
    
    public function assignDamage($carDamages, FoDamage $damageToAssign, $badRoll = null) : int {
        return $this->assignDamageSimple($carDamages,
                $damageToAssign->wear_points,
                $damageToAssign->fo_e_damage_type_id,
                $badRoll);
    }
    
    public function assignDamageSimple($carDamages, int $wearPoints, int $foEDamageTypeId, $badRoll = null, $save = false) : int {
        $damageWearPoints = 0;
        if ($badRoll == null) {
            $damageWearPoints = $wearPoints;
        } else {
            for ($i = 0; $i < $wearPoints; $i++) {
                if ($this->DiceLogic->getRoll(0) <= $badRoll) {
                    $damageWearPoints++;
                }
            }
        }
        
        if ($damageWearPoints > 0) {
            $updatedDamage = collection($carDamages)->
                    firstMatch(['fo_e_damage_type_id' => $foEDamageTypeId]);
            $updatedDamage->wear_points -= $damageWearPoints;

            if ($save) {
                $this->save($updatedDamage);
                $foLog = new FoLog([
                    'fo_car_id' => $updatedDamage->fo_car_id,
                    'type' => 'D',
                    'fo_damages' => [
                        new FoDamage([
                            'fo_e_damage_type_id' => $foEDamageTypeId,
                            'wear_points' => $damageWearPoints,
                        ])
                    ],
                ]);
                $this->FoLogs->save($foLog, ['associated' => ['FoDamages']]);
            }
        }
        
        return $damageWearPoints;
    }
    
    /**
     * 
     * @param CollectionInterface $foCarDamages
     * @param int $foEDamageTypeId
     * 
     * @return FoDamage damage that was dealt to the car for FoLog
     */    
    private function processDamage(CollectionInterface $foCarDamages, $foEDamageTypeId) {
        $foCarDamages->firstMatch(['fo_e_damage_type_id' => $foEDamageTypeId])
            ->wear_points--;
        return new FoDamage([
            'fo_e_damage_type_id' => $foEDamageTypeId,
            'wear_points' => 1,
        ]);
    }
    
    public function processGearChangeDamage(FoCar $foCar, int $gearDiff) {
        $foLogDamages = [];
        $carDamages = collection($foCar->fo_damages);
        switch ($gearDiff) {
            case (-4):  //engine damage
                $foLogDamages[] = $this->processDamage($carDamages, 4);
            case (-3):  //brakes damage
                $foLogDamages[] = $this->processDamage($carDamages, 3);
            case (-2):  //gearbox damage
                $foLogDamages[] = $this->processDamage($carDamages, 2);
                break;
        }
        $this->saveMany($carDamages->toList());
        return $foLogDamages;
    }
}
