<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FoTraverses Model
 *
 * @property \App\Model\Table\FoMoveOptionsTable&\Cake\ORM\Association\BelongsTo $FoMoveOptions
 * @property \App\Model\Table\FoPositionsTable&\Cake\ORM\Association\BelongsTo $FoPositions
 *
 * @method \App\Model\Entity\FoTraverse newEmptyEntity()
 * @method \App\Model\Entity\FoTraverse newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FoTraverse[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FoTraverse get($primaryKey, $options = [])
 * @method \App\Model\Entity\FoTraverse findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FoTraverse patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FoTraverse[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FoTraverse|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoTraverse saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoTraverse[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoTraverse[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoTraverse[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoTraverse[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FoTraversesTable extends Table
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

        $this->setTable('fo_traverses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('FoMoveOptions', [
            'foreignKey' => 'fo_move_option_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('FoPositions', [
            'foreignKey' => 'fo_position_id',
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
        $rules->add($rules->existsIn(['fo_move_option_id'], 'FoMoveOptions'), ['errorField' => 'fo_move_option_id']);
        $rules->add($rules->existsIn(['fo_position_id'], 'FoPositions'), ['errorField' => 'fo_position_id']);

        return $rules;
    }
}
