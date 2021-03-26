<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GameTypes Model
 *
 * @property \App\Model\Table\GamesTable&\Cake\ORM\Association\HasMany $Games
 *
 * @method \App\Model\Entity\GameType newEmptyEntity()
 * @method \App\Model\Entity\GameType newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\GameType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GameType get($primaryKey, $options = [])
 * @method \App\Model\Entity\GameType findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\GameType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GameType[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\GameType|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GameType saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GameType[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\GameType[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\GameType[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\GameType[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class GameTypesTable extends Table
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

        $this->setTable('game_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Games', [
            'foreignKey' => 'game_type_id',
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
            ->notEmptyString('name')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('table_prefix')
            ->maxLength('table_prefix', 2)
            ->requirePresence('table_prefix', 'create')
            ->notEmptyString('table_prefix')
            ->add('table_prefix', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
        $rules->add($rules->isUnique(['name']), ['errorField' => 'name']);
        $rules->add($rules->isUnique(['table_prefix']), ['errorField' => 'table_prefix']);

        return $rules;
    }
}
