<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FoGames Model
 *
 * @property \App\Model\Table\GamesTable&\Cake\ORM\Association\BelongsTo $Games
 * @property \App\Model\Table\FoTracksTable&\Cake\ORM\Association\BelongsTo $FoTracks
 *
 * @method \App\Model\Entity\FoGame newEmptyEntity()
 * @method \App\Model\Entity\FoGame newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FoGame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FoGame get($primaryKey, $options = [])
 * @method \App\Model\Entity\FoGame findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FoGame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FoGame[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FoGame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoGame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoGame[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoGame[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoGame[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoGame[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FoGamesTable extends Table
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

        $this->setTable('fo_games');
        $this->setDisplayField('game_id');
        $this->setPrimaryKey('game_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Games', [
            'foreignKey' => 'game_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('FoTracks', [
            'foreignKey' => 'fo_track_id',
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
            ->integer('cars_per_player')
            ->notEmptyString('cars_per_player');

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
        $rules->add($rules->existsIn(['fo_track_id'], 'FoTracks'), ['errorField' => 'fo_track_id']);

        return $rules;
    }
}
