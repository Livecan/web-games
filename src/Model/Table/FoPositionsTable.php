<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FoPositions Model
 *
 * @property \App\Model\Table\FoTracksTable&\Cake\ORM\Association\BelongsTo $FoTracks
 * @property \App\Model\Table\FoCurvesTable&\Cake\ORM\Association\BelongsTo $FoCurves
 * @property \App\Model\Table\FoCarsTable&\Cake\ORM\Association\HasMany $FoCars
 * @property \App\Model\Table\FoDebrisTable&\Cake\ORM\Association\HasMany $FoDebris
 * @property \App\Model\Table\FoLogsTable&\Cake\ORM\Association\HasMany $FoLogs
 * @property \App\Model\Table\FoMoveOptionsTable&\Cake\ORM\Association\HasMany $FoMoveOptions
 *
 * @method \App\Model\Entity\FoPosition newEmptyEntity()
 * @method \App\Model\Entity\FoPosition newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FoPosition[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FoPosition get($primaryKey, $options = [])
 * @method \App\Model\Entity\FoPosition findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FoPosition patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FoPosition[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FoPosition|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoPosition saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoPosition[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoPosition[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoPosition[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoPosition[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FoPositionsTable extends Table
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

        $this->setTable('fo_positions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('FoTracks', [
            'foreignKey' => 'fo_track_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('FoCurves', [
            'foreignKey' => 'fo_curve_id',
        ]);
        $this->hasMany('FoCars', [
            'foreignKey' => 'fo_position_id',
        ]);
        $this->hasMany('FoDebris', [
            'foreignKey' => 'fo_position_id',
        ]);
        $this->hasMany('FoLogs', [
            'foreignKey' => 'fo_position_id',
        ]);
        $this->hasMany('FoMoveOptions', [
            'foreignKey' => 'fo_position_id',
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
            ->integer('order')
            ->requirePresence('order', 'create')
            ->notEmptyString('order');

        $validator
            ->boolean('is_finish')
            ->notEmptyString('is_finish');

        $validator
            ->integer('starting_position')
            ->allowEmptyString('starting_position');

        $validator
            ->integer('pos_x')
            ->requirePresence('pos_x', 'create')
            ->notEmptyString('pos_x');

        $validator
            ->integer('pos_y')
            ->requirePresence('pos_y', 'create')
            ->notEmptyString('pos_y');

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
        $rules->add($rules->existsIn(['fo_track_id'], 'FoTracks'), ['errorField' => 'fo_track_id']);
        $rules->add($rules->existsIn(['fo_curve_id'], 'FoCurves'), ['errorField' => 'fo_curve_id']);

        return $rules;
    }
}
