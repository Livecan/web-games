<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DrTokens Model
 *
 * @property \App\Model\Table\GamesTable&\Cake\ORM\Association\BelongsToMany $Games
 *
 * @method \App\Model\Entity\DrToken newEmptyEntity()
 * @method \App\Model\Entity\DrToken newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DrToken[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DrToken get($primaryKey, $options = [])
 * @method \App\Model\Entity\DrToken findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DrToken patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DrToken[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DrToken|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DrToken saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DrToken[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrToken[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrToken[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrToken[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DrTokensTable extends Table
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

        $this->setTable('dr_tokens');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Games', [
            'foreignKey' => 'dr_token_id',
            'targetForeignKey' => 'game_id',
            'joinTable' => 'dr_tokens_games',
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
            ->integer('type')
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->integer('value')
            ->requirePresence('value', 'create')
            ->notEmptyString('value');

        return $validator;
    }
}
