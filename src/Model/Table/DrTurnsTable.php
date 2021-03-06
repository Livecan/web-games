<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * DrTurns Model
 *
 * @property \App\Model\Table\GamesTable&\Cake\ORM\Association\BelongsTo $Games
 *
 * @method \App\Model\Entity\DrTurn newEmptyEntity()
 * @method \App\Model\Entity\DrTurn newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DrTurn[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DrTurn get($primaryKey, $options = [])
 * @method \App\Model\Entity\DrTurn findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DrTurn patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DrTurn[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DrTurn|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DrTurn saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DrTurn[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrTurn[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrTurn[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DrTurn[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DrTurnsTable extends Table
{
    use LocatorAwareTrait;
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

        $this->setTable('dr_turns');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Games', [
            'foreignKey' => 'game_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }
    
    public function getTableGamesUsers(): GamesUsersTable {
        if (!isset($this->gamesUsers)) {
            $this->gamesUsers = $this->getTableLocator()->get('GamesUsers');
        }
        return $this->gamesUsers;
    }
    
    public function getTableDrTokensGames(): DrTokensGamesTable {
        if (!isset($this->drTokensGames)) {
            $this->drTokensGames = $this->getTableLocator()->get('DrTokensGames');
        }
        return $this->drTokensGames;
    }
    
    public function getTableDrowningGames(): DrowningGamesTable {
        if (!isset($this->drowningGames)) {
            $this->drowningGames = $this->getTableLocator()->get('DrowningGames');
        }
        return $this->drowningGames;
    }
    
    public function getTableDrResults(): DrResultsTable {
        if (!isset($this->drResults)) {
            $this->drResults = $this->getTableLocator()->get('DrResults');
        }
        return $this->drResults;
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
            ->integer('position')
            ->requirePresence('position', 'create')
            ->notEmptyString('position');

        $validator
            ->integer('round')
            ->requirePresence('round', 'create')
            ->notEmptyString('round');

        $validator
            ->maxLength('roll', 3)
            ->requirePresence('roll', 'create')
            ->notEmptyString('roll');

        $validator
            ->boolean('returning')
            ->notEmptyString('returning');

        $validator
            ->boolean('taking')
            ->notEmptyString('taking');
        
        $validator
            ->integer('oxygen');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
    
    /**
     * Returns an array of Entities - '->position', '->user' and '->user->order_number'.
     * 
     * @param int $game_id
     * @return array of Entities
     */
    public function getPositionPlayer($game_id, $round = null) {
        $playerCount = $this->getTableGamesUsers()->find()->where(['game_id' => $game_id])->count();
        $positionPlayersQuery = $this->find('all', ['order' => ['created' => 'DESC']])->
                contain(['Users' =>
                    ['Games' =>
                        function (Query $q) use ($game_id) {
                            return $q->select(['GamesUsers.order_number'])->where(['game_id' => $game_id]);
                        }
                    ]
                ])->
                select(['position'])->
                select($this->Users)->
                where(['game_id' => $game_id]);
        if ($round != null) {
            $positionPlayersQuery->where(['round' => $round]);
        }
        $positionPlayers = $positionPlayersQuery->
                limit($playerCount)->
                all()->
                toArray();
        foreach ($positionPlayers as $positionPlayer) {
            $positionPlayer->user->order_number = $positionPlayer->user->games[0]->_joinData->order_number;
            $positionPlayer->unset('games');
        }
        return $positionPlayers;
    }
    
    /**
     * Returns the current oxygen level for $game_id.
     * 
     * @param int $game_id
     * @return int
     */
    public function getOxygenLevel($game_id) {
        return $this->find('all', ['order' => ['created' => 'DESC']])->
                select('oxygen')->
                where(['game_id' => $game_id])->
                first()->
                oxygen;
    }
    
    /**
     * Returns User who's turn it is in the $game_id.
     * 
     * @param int $game_id
     * @return App\Model\Entity\User
     */
    public function getCurrentTurnUser($game_id) {
        return $this->find('all', ['order' => ['DrTurns.created' => 'DESC', 'DrTurns.id' => 'DESC']])->
                contain(['Users'])->
                select($this->Users)->
                where(['game_id' => $game_id])->
                first()->user;
    }
    
    /**
     * Returns the last turn done or being done in the $game_id.
     * 
     * @param int $game_id
     * @return App\Model\Entity\DrTurn
     */
    public function getLastTurn($game_id) {
        return $this->find('all', ['order' => ['id' => 'DESC']])->
            where(['game_id' => $game_id])->
            first();
    }
    
    /**
     * Returns true if the player can take treasure in the last turn according
     * to the $board parameter.
     * 
     * @param type $board
     * @return boolean
     */
    public function canTakeTreasure($board) {
        return !$board->last_turn->dropping && $board->depths[$board->last_turn->position]->tokens;
    }
    
    /**
     * Returns true if the player can drop treasure in the last turn according
     * to the $board parameter.
     * 
     * @param type $board
     * @return boolean
     */
    public function canDropTreasure($board) {   //TODO: fix this!
        return empty($board->depths[$board->last_turn->position]->tokens) &&
                !$board->last_turn->taking;
    }
    
    /**
     * Processes user actions.
     * 
     * @param type $board
     * @param type $data
     * @param type $user
     * @return boolean
     */
    public function processActions($board, $data, $user) {
        if ($board->last_turn->id != $data['turn_id']) {
            return false;
        }
        if (array_key_exists('start_returning', $data)) {
            if ($data['start_returning']) {
                $board->last_turn->returning = true;
            }
            $this->save($board->last_turn);
        }
        
        if ($this->canTakeTreasure($board) && array_key_exists('taking', $data) && $data['taking']) {
            $gameTokens = $this->getTableDrTokensGames()->find('all')->
                where(['game_id' => $board->id, 'position' => $board->last_turn->position])->
                toArray();
            foreach ($gameTokens as $gameToken) {
                $gameToken->group_number = $gameTokens[0]->dr_token_id;
                $gameToken->user_id = $user->id;
                $gameToken->position = null;
                $gameToken->dr_token_state_id = 2;
            }
            $this->getTableDrTokensGames()->saveMany($gameTokens);
            
            $board->last_turn->taking = true;
            $this->save($board->last_turn);
        }
        
        if ($this->canDropTreasure($board) && array_key_exists('dropping', $data) && $data['dropping']) {
            $gameTokens = $this->getTableDrTokensGames()->find('all')->
                    where(['game_id' => $board->id, 'group_number' => $data['group_number']])->
                    toArray();
            foreach ($gameTokens as $gameToken) {
                $gameToken->user_id = null;
                $gameToken->position = $board->last_turn->position;
                $gameToken->dr_token_state_id = 1;
            }
            $this->getTableDrTokensGames()->saveMany($gameTokens);
            $board->last_turn->dropping = true;
            $this->save($board->last_turn);
        }
        
        $this->processTurns($board = $this->getTableDrowningGames()->getBoard($board), array_key_exists('finish', $data) && $data['finish']);
        
        return true;
    }
    
    /**
     * Generates random roll of two dice with numbers 1-3 and returns an array.
     * 
     * @return array of int
     */
    public function getRoll() {
        return [rand(1, 3), rand(1, 3)];
    }
    
    private function processTurns($board, $finished) {
        $endOfRound = false;
        while (!$endOfRound &&
                ($finished || (($board = $this->getTableDrowningGames()->getBoard($board))->last_turn->returning
                && !$this->canTakeTreasure($board) && !$this->canDropTreasure($board)) || $board->last_turn->position < 1)) {
            $nextUserId = $board->last_turn->user_id;
            do {
                $nextUser = $this->getTableGamesUsers()->getNextUser($board->id, $nextUserId);
                $nextUserId = $nextUser->id;
            } while (count($board->outDivers) < $board->users &&
                    count(array_filter($board->outDivers, function ($user) use ($nextUser)
                { return $user->id == $nextUser->id; })) > 0);
            
            $roll = $this->getRoll();
            $lastUserTakenTreasuresCount = $this->getTableDrTokensGames()->getUserTakenTreasuresCount($board->id, $board->last_turn->user_id);
            $nextUserTakenTreasuresCount = $this->getTableDrTokensGames()->getUserTakenTreasuresCount($board->id, $nextUser->id);
            $moveCount = max(array_sum($roll) - $nextUserTakenTreasuresCount, 0);
            $nextPlayerLastTurn = $this->find('all')->
                    where(['game_id' => $board->id, 'user_id' => $nextUser->id, 'round' => $board->last_turn->round])->
                    order(['created' => 'DESC'])->
                    first();
            $nextPlayerLastPosition = $nextPlayerLastTurn ? $nextPlayerLastTurn->position : 0;
            $nextPlayerReturning = $nextPlayerLastTurn ? $nextPlayerLastTurn->returning : 0;
            $position = $this->processMove($board, $nextPlayerLastPosition, $moveCount, $nextPlayerReturning);
            $nextTurnReturning = false;
            if ($position == $this->getMaxDepth() ||
                    ($nextPlayerLastTurn && $nextPlayerLastTurn->returning)) {    //if the player reaches bottom, he must return.
                $nextTurnReturning = true;
            }
            
            $nextTurn = $this->newEntity(['game_id' => $board->id,
                'user_id' => $nextUser->id,
                'position' => $position,
                'round' => $board->last_turn->round,
                'roll' => $roll[0] . '+' . $roll[1],
                'returning' => $nextTurnReturning,
                'taking' => false,
                'oxygen' => $board->last_turn->oxygen - $lastUserTakenTreasuresCount,
                ]);
            $this->save($nextTurn);
            $finished = false;
            $board = $this->getTableDrowningGames()->getBoard($board);
            $endOfRound = $this->isEndRound($board);
            if ($endOfRound) {
                $this->getTableDrowningGames()->startNewRound($board);
                $board = $this->getTableDrowningGames()->getBoard($board);
                if ($board->last_turn->round > 3) {
                    $this->processEndGame($board);
                }
            }
        }
    }
    
    /**
     * Moves a player by $moveCount from his current $position on the $board
     * depending on whether the player is $returning and returns the final
     * position.
     * 
     * @param type $board
     * @param int $position
     * @param int $moveCount
     * @param boolean $returning
     * @return int
     */
    private function processMove($board, $position, $moveCount, $returning) {
        if ($moveCount > 0 && ($returning || $position < $this->getMaxDepth())) {
            do {
                if ($returning) {
                    $position--;
                } else {
                    $position++;
                }
                if ($position > 0 && !$board->depths[$position]->diver) {
                    $moveCount--;
                }
            } while ($moveCount > 0 && $position > 0 && $position < $this->getMaxDepth());
        }
        return $position;
    }
    
    private function processEndGame($board) {
        $game = $this->getTableDrowningGames()->get($board->id);
        $game->game_state_id = 3;   //FINISHED
        $this->getTableDrowningGames()->save($game);
        $usersScoreQuery = $this->getTableDrTokensGames()->find('all');
        $usersScore = $usersScoreQuery->where(['game_id' => $board->id])->
                whereNotNull('user_id')->
                group('user_id')->
                contain(['DrTokens'])->
                select(['user_id', 'game_id'])->
                select(['score' => $usersScoreQuery->func()->sum('DrTokens.value')])->
                toArray();
        $results = [];
        foreach ($usersScore as $userScore) {
            $results[] = $this->getTableDrResults()->newEntity(
                    ['game_id' => $userScore->game_id, 'user_id' => $userScore->user_id, 'score' => (int)$userScore->score]);
        }
        $this->getTableDrResults()->saveMany($results);
    }
    
    private $maxDepth = 20; //TODO: refactor constant
    
    public function getMaxDepth() {
        return $this->maxDepth;
    }
    
    private function isEndRound($board) {
        return $board->last_turn->oxygen <= 0 || is_array($board->outDivers) && count($board->users) == count($board->outDivers);
    }
}
