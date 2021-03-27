<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
class FoDamagesTable extends Table
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

        $this->setTable('fo_damages');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('FoCars', [
            'foreignKey' => 'fo_car_id',
            'joinType' => 'INNER',
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
}
