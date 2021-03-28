<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FoCurves Model
 *
 * @property \App\Model\Table\FoTracksTable&\Cake\ORM\Association\BelongsTo $FoTracks
 * @property \App\Model\Table\FoCurvesTable&\Cake\ORM\Association\BelongsTo $FoCurves
 * @property \App\Model\Table\FoPositionsTable&\Cake\ORM\Association\HasMany $FoPositions
 *
 * @method \App\Model\Entity\FoCurve newEmptyEntity()
 * @method \App\Model\Entity\FoCurve newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FoCurve[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FoCurve get($primaryKey, $options = [])
 * @method \App\Model\Entity\FoCurve findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FoCurve patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FoCurve[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FoCurve|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoCurve saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoCurve[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoCurve[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoCurve[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoCurve[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FoCurvesTable extends Table
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

        $this->setTable('fo_curves');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('FoTracks', [
            'foreignKey' => 'fo_track_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('FoCurves', [
            'foreignKey' => 'fo_next_curve_id',
        ]);
        $this->hasMany('FoPositions', [
            'foreignKey' => 'fo_curve_id',
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
            ->integer('stops')
            ->requirePresence('stops', 'create')
            ->notEmptyString('stops');

        $validator
            ->scalar('name')
            ->maxLength('name', 20)
            ->allowEmptyString('name');

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
        $rules->add($rules->existsIn(['fo_next_curve_id'], 'FoCurves'), ['errorField' => 'fo_next_curve_id']);

        return $rules;
    }
}
