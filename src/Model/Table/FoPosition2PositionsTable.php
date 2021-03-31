<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FoPosition2Positions Model
 *
 * @property \App\Model\Table\FoPositionsTable&\Cake\ORM\Association\BelongsTo $FoPositions
 * @property \App\Model\Table\FoPositionsTable&\Cake\ORM\Association\BelongsTo $FoPositions
 *
 * @method \App\Model\Entity\FoPosition2Position newEmptyEntity()
 * @method \App\Model\Entity\FoPosition2Position newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FoPosition2Position[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FoPosition2Position get($primaryKey, $options = [])
 * @method \App\Model\Entity\FoPosition2Position findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FoPosition2Position patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FoPosition2Position[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FoPosition2Position|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoPosition2Position saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoPosition2Position[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoPosition2Position[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoPosition2Position[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoPosition2Position[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FoPosition2PositionsTable extends Table
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

        $this->setTable('fo_position2positions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('FoPositionFrom', [
            'className' => 'FoPositions',
            'foreignKey' => 'fo_position_from_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('FoPositionTo', [
            'className' => 'FoPositions',
            'foreignKey' => 'fo_position_to_id',
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
            ->boolean('is_left')
            ->notEmptyString('is_left');

        $validator
            ->boolean('is_straight')
            ->notEmptyString('is_straight');

        $validator
            ->boolean('is_right')
            ->notEmptyString('is_right');

        $validator
            ->boolean('is_curve')
            ->notEmptyString('is_curve');

        $validator
            ->boolean('is_equal_distance')
            ->notEmptyString('is_equal_distance');

        $validator
            ->boolean('is_pitlane_move')
            ->notEmptyString('is_pitlane_move');

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
        $rules->add($rules->existsIn(['fo_position_from_id'], 'FoPositions'), ['errorField' => 'fo_position_from_id']);
        $rules->add($rules->existsIn(['fo_position_to_id'], 'FoPositions'), ['errorField' => 'fo_position_to_id']);

        return $rules;
    }
}
