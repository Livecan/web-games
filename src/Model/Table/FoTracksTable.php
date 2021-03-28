<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FoTracks Model
 *
 * @property \App\Model\Table\FoCurvesTable&\Cake\ORM\Association\HasMany $FoCurves
 * @property \App\Model\Table\FoGamesTable&\Cake\ORM\Association\HasMany $FoGames
 * @property \App\Model\Table\FoPositionsTable&\Cake\ORM\Association\HasMany $FoPositions
 *
 * @method \App\Model\Entity\FoTrack newEmptyEntity()
 * @method \App\Model\Entity\FoTrack newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FoTrack[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FoTrack get($primaryKey, $options = [])
 * @method \App\Model\Entity\FoTrack findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FoTrack patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FoTrack[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FoTrack|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoTrack saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoTrack[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoTrack[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoTrack[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoTrack[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FoTracksTable extends Table
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

        $this->setTable('fo_tracks');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('FoCurves', [
            'foreignKey' => 'fo_track_id',
        ]);
        $this->hasMany('FoGames', [
            'foreignKey' => 'fo_track_id',
        ]);
        $this->hasMany('FoPositions', [
            'foreignKey' => 'fo_track_id',
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
            ->scalar('name')
            ->maxLength('name', 20)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('game_plan')
            ->maxLength('game_plan', 50)
            ->requirePresence('game_plan', 'create')
            ->notEmptyString('game_plan');

        return $validator;
    }
}
