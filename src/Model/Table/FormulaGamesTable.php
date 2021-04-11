<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FormulaGames Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\GameStatesTable&\Cake\ORM\Association\BelongsTo $GameStates
 * @property \App\Model\Table\GameTypesTable&\Cake\ORM\Association\BelongsTo $GameTypes
 * @property \App\Model\Table\DrResultsTable&\Cake\ORM\Association\HasMany $DrResults
 * @property \App\Model\Table\DrTurnsTable&\Cake\ORM\Association\HasMany $DrTurns
 * @property \App\Model\Table\FoCarsTable&\Cake\ORM\Association\HasMany $FoCars
 * @property \App\Model\Table\FoDebrisTable&\Cake\ORM\Association\HasMany $FoDebris
 * @property \App\Model\Table\FoGamesTable&\Cake\ORM\Association\HasMany $FoGames
 * @property \App\Model\Table\DrTokensTable&\Cake\ORM\Association\BelongsToMany $DrTokens
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsToMany $Users
 *
 * @method \App\Model\Entity\FormulaGame newEmptyEntity()
 * @method \App\Model\Entity\FormulaGame newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FormulaGame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FormulaGame get($primaryKey, $options = [])
 * @method \App\Model\Entity\FormulaGame findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FormulaGame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FormulaGame[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FormulaGame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FormulaGame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FormulaGame[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FormulaGame[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FormulaGame[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FormulaGame[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FormulaGamesTable extends Table
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

        $this->setTable('Games');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'creator_id',
            'joinType' => 'INNER',
        ])->setName('creator');
        $this->belongsTo('GameStates', [
            'foreignKey' => 'game_state_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('GameTypes', [
            'foreignKey' => 'game_type_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('FoCars', [
            'foreignKey' => 'game_id',
        ]);
        $this->hasMany('FoDebris', [
            'foreignKey' => 'game_id',
        ]);
        $this->hasOne('FoGames', [
            'foreignKey' => 'game_id',
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'game_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'games_users',
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
            ->maxLength('name', 30)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

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
        $rules->add($rules->existsIn(['creator_id'], 'Users'), ['errorField' => 'creator_id']);
        $rules->add($rules->existsIn(['game_state_id'], 'GameStates'), ['errorField' => 'game_state_id']);
        $rules->add($rules->existsIn(['game_type_id'], 'GameTypes'), ['errorField' => 'game_type_id']);

        return $rules;
    }    
}
