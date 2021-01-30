<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GameStates Model
 *
 * @property \App\Model\Table\GamesTable&\Cake\ORM\Association\HasMany $Games
 *
 * @method \App\Model\Entity\GameState newEmptyEntity()
 * @method \App\Model\Entity\GameState newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\GameState[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GameState get($primaryKey, $options = [])
 * @method \App\Model\Entity\GameState findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\GameState patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GameState[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\GameState|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GameState saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GameState[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\GameState[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\GameState[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\GameState[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class GameStatesTable extends Table
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

        $this->setTable('game_states');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Games', [
            'foreignKey' => 'game_state_id',
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

        return $validator;
    }
}
