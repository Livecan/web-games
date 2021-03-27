<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FoEDamageTypes Model
 *
 * @property \App\Model\Table\FoDamagesTable&\Cake\ORM\Association\HasMany $FoDamages
 *
 * @method \App\Model\Entity\FoEDamageType newEmptyEntity()
 * @method \App\Model\Entity\FoEDamageType newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FoEDamageType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FoEDamageType get($primaryKey, $options = [])
 * @method \App\Model\Entity\FoEDamageType findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FoEDamageType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FoEDamageType[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FoEDamageType|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoEDamageType saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FoEDamageType[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoEDamageType[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoEDamageType[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FoEDamageType[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FoEDamageTypesTable extends Table
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

        $this->setTable('fo_e_damage_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('FoDamages', [
            'foreignKey' => 'fo_e_damage_type_id',
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
