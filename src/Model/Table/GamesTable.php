<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Games Model
 *
 * @property \App\Model\Table\DrTurnsTable&\Cake\ORM\Association\HasMany $DrTurns
 * @property \App\Model\Table\DrTokensTable&\Cake\ORM\Association\BelongsToMany $DrTokens
 *
 * @method \App\Model\Entity\Game newEmptyEntity()
 * @method \App\Model\Entity\Game newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Game[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Game get($primaryKey, $options = [])
 * @method \App\Model\Entity\Game findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Game patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Game[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Game|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Game saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Game[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Game[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Game[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Game[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class GamesTable extends Table
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
        
        $this->addBehavior('Timestamp');

        $this->setTable('games');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        
        $this->belongsTo('GameStates', [
            'foreignKey' => 'game_state_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('GameTypes', [
            'foreignKey' => 'game_type_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'game_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'games_users',
        ]);
        $this->hasOne('Creator', [
            'foreignKey' => 'creator_id',
            'targetTable' => 'users',
            'targetForeignKey' => 'id',
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

        $validator
            ->scalar('name')
            ->maxLength('name', 30)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }
    
    public function addGame($data, $user) {
        if (!$data) {
            return false;
        }
        
        $game = $this->newEntity($data);
        $game->creator_id = $user->id;
        $game->users = [$user];
        $result = $this->save($game);
        
        if (!$result) {
            return false;
        }
        
        return $result;
    }
    
    public function addUser($game, $user) {
        //add a new $user to $game and setDirty to upload when ->save
        $game->users[] = $user;
        $game->setDirty('users',true);
        
        $result = $this->save($game, ['associated' => ['Users']]);
        if (!$result) {
            return false;
        }
        return true;
    }
    
    public function getUsers($game_id) {
        return $this->get($game_id, ['contain' => 'Users'])->users;
    }
}
