<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Entity\FoMoveOption;

/**
 * FoMoveOptions Model
 *
 * @property \App\Model\Table\FoCarsTable&\Cake\ORM\Association\BelongsTo $FoCars
 * @property \App\Model\Table\FoPositionsTable&\Cake\ORM\Association\BelongsTo $FoPositions
 * @property \App\Model\Table\FoDamagesTable&\Cake\ORM\Association\HasMany $FoDamages
 *
 * @method \App\Model\Entity\FoMoveOption newEmptyEntity()
 * @method \App\Model\Entity\FoMoveOption newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FoMoveOption[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FoMoveOption get($primaryKey, $options = [])
 * @method \App\Model\Entity\FoMoveOption findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FoMoveOption patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FoMoveOption[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FoMoveOption|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoMoveOption saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoMoveOption[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoMoveOption[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoMoveOption[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoMoveOption[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FoMoveOptionsTable extends Table
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

        $this->setTable('fo_move_options');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('FoCars', [
            'foreignKey' => 'fo_car_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('FoPositions', [
            'foreignKey' => 'fo_position_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('FoDamages', [
            'foreignKey' => 'fo_move_option_id',
        ]);
        $this->hasMany('FoTraverses', [
            'foreignKey' => 'fo_move_option_id',
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
}
