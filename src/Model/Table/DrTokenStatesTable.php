<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DrTokenStates Model
 *
 * @property \App\Model\Table\DrTokensGamesTable&\Cake\ORM\Association\HasMany $DrTokensGames
 *
 * @method \App\Model\Entity\DrTokenState newEmptyEntity()
 * @method \App\Model\Entity\DrTokenState newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DrTokenState[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DrTokenState get($primaryKey, $options = [])
 * @method \App\Model\Entity\DrTokenState findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DrTokenState patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DrTokenState[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DrTokenState|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DrTokenState saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DrTokenState[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrTokenState[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrTokenState[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrTokenState[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DrTokenStatesTable extends Table
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

        $this->setTable('dr_token_states');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('DrTokensGames', [
            'foreignKey' => 'dr_token_state_id',
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
            ->maxLength('name', 10)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }
}
