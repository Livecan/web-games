<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Table;

use App\Model\Table\GamesTable;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Entity;

/**
 * Description of DrowningGame
 *
 * @author roman
 */
class DrowningGamesTable extends GamesTable {
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
        
        $this->hasMany('DrTurns', [
            'foreignKey' => 'game_id',
        ]);
        $this->belongsToMany('DrTokens', [
            'foreignKey' => 'game_id',
            'targetForeignKey' => 'dr_token_id',
            'joinTable' => 'dr_tokens_games',
        ]);
    }
    
    private function getTableDrTokensGames(): DrTokensGamesTable {
        if (!isset($this->drTokensGames)) {
            $this->drTokensGames = $this->getTableLocator()->get('DrTokensGames');
        }
        return $this->drTokensGames;
    }
    
    private function getTableDrTurns(): DrTurnsTable {
        if (!isset($this->drTurns)) {
            $this->drTurns = $this->getTableLocator()->get('DrTurns');
        }
        return $this->drTurns;
    }
    
    private function getTableUsers(): UsersTable {
        if (!isset($this->users)) {
            $this->users = $this->getTableLocator()->get('Users');
        }
        return $this->users;
    }
    
    private function getTableGamesUsers(): GamesUsersTable {
        if (!isset($this->gamesUsers)) {
            $this->gamesUsers = $this->getTableLocator()->get('GamesUsers');
        }
        return $this->gamesUsers;
    }
    
    private function randomizePlayerOrder($users) {
        usort($users, function($_a, $_b) { return rand(-1, 1); });
        for ($i = 0; $i < count($users); $i++) {
            $users[$i]->_joinData->order_number = $i;
            $users[$i]->_joinData->next_user_id = $users[($i + 1) % count($users)]->id;
        }
    }
    
    private function getRandomizedTokens() {
        $tokens = $this->DrTokens->find('all')->toArray();
        usort($tokens,
                function($_a, $_b) {
                    return $_a->type == $_b->type ?
                        rand(-1, 1) : $_a->type > $_b->type;
                });

        for ($i = 0; $i < count($tokens); $i++) {
            $tokens[$i]->_joinData =
                $this->getTableDrTokensGames()->newEntity(['position' => $i + 1]);
        }
        return $tokens;
    }
    
    public function startNewRound($board) {
        //firstly all the players who made it up claim their tokens
        if (!empty($board->outDivers)) {
            $this->getTableDrTokensGames()->claimPlayersTokens($board->id,
                    array_map(function($_user) { return $_user->id; }, $board->outDivers));
        }
        
        //secondly all the tokens on board are shifted so that there are no gaps
        $shiftCorrection = 0;
        for ($depth = 1; $depth < $this->getTableDrTurns()->getMaxDepth(); $depth++) {
            if (empty($board->depths[$depth]->tokens)) {
                $this->getTableDrTokensGames()->shiftTokens($board->id, $depth - $shiftCorrection);
                $shiftCorrection++;
            }
        }
        $lastPosition = $this->getTableDrTurns()->getMaxDepth() - $shiftCorrection;
        
        $playerTokens = $this->getTableDrTokensGames()->getPlayersTokens($board->id);
        $tokensToDrop = [];
        $_user_id = $board->last_turn->user_id;
        do {
            $playerTokenGroupsToDrop = $playerTokens[$_user_id][2];
            if (!empty($playerTokenGroupsToDrop)) {
                foreach($playerTokenGroupsToDrop as $playerTokensToDrop) {
                    $tokensToDrop = array_merge($tokensToDrop, $playerTokensToDrop);
                }
            }
            $_user_id = $this->getTableGamesUsers()->getNextUser($board->id, $_user_id)->id;
        } while ($_user_id != $board->last_turn->user_id);
        
        $tokenGroup = [];
        foreach($tokensToDrop as $_tokenToDrop) {
            $tokenGroup[] = $_tokenToDrop;
            if (count($tokenGroup) == 3) {
                $lastPosition++;
                $this->getTableDrTokensGames()->placeDroppedTokens($board->id, $lastPosition,
                        array_map(function($_token) { return $_token->id; }, $tokenGroup));
                $tokenGroup = [];
            }
        }
        if (count($tokenGroup) < 3 && !empty($tokenGroup)) {
            $lastPosition++;
            $this->getTableDrTokensGames()->placeDroppedTokens($board->id, $lastPosition,
                    array_map(function($_token) { return $_token->id; }, $tokenGroup));
        }
        
        //TODO: create first move and increase ->round
    }
    
    public function start($game) {
        $game->users = $this->getUsers($game->id);
        
        //firstly randomize player order
        $this->randomizePlayerOrder($game->users);
        $game->setDirty('users', true);

        //secondly randomly deal tokens
        $game->dr_tokens = $this->getRandomizedTokens();

        //thirdly change game state to started
        $game->game_state_id = 2;

        $firstRoll = $this->getTableDrTurns()->getRoll();

        $firstTurn = $this->getTableDrTurns()->newEntity(['user_id' => $game->users[0]->id,
                        'position' => array_sum($firstRoll),
                        'round' => 1,
                        'roll' => $firstRoll[0] . '+' . $firstRoll[1],
                        ]);
        $game->dr_turns = [$firstTurn];

        $result = $this->save($game, ['associated' => ['Users', 'DrTokens', 'DrTurns']]);
        
        if (!$result) {
            return false;
        }

        return true;
    }
    
    public function getBoard($game, $currentUser = null) {
        $board = new Entity();
        
        $board->id = $game->id;
        
        $board->depths = [];

        for ($depth = 1; $depth <= $this->getTableDrTurns()->getMaxDepth(); $depth++) {
            $board->depths[$depth] = new Entity();
        }
        
        $board->outDivers = [];
        foreach($this->getTableDrTurns()->getPositionPlayer($game->id) as $positionPlayer) {
            if ($positionPlayer->position > 0) {
                $board->depths[$positionPlayer->position]->diver = $positionPlayer->user;
            }
            else {
                $board->outDivers[] = $positionPlayer->user;
            }
        }

        //get tokens by depth and add to $board
        $tokensByPosition = $this->getTableDrTokensGames()->getTokensByPosition($game->id);
        for ($depth = 1; $depth <= $this->getTableDrTurns()->getMaxDepth(); $depth++) {
            if (array_key_exists($depth, $tokensByPosition)) {
                $board->depths[$depth]->tokens = $tokensByPosition[$depth];
            } else {
                $board->depths[$depth]->tokens = [];
            }
        }

        $board->users = $game->users;
        usort($board->users, function($_before, $_after) {
            return $_before->_joinData->order < $_after->_joinData->order ?
                1 : -1; });
        $playersTokens = $this->getTableDrTokensGames()->getPlayersTokens($board->id);
        foreach ($board->users as $_user) {
            if (array_key_exists($_user->id, $playersTokens)) {
                $_user->tokens = $playersTokens[$_user->id];
            }
        }
        
        $board->oxygen = $this->getTableDrTurns()->getOxygenLevel($game->id);
        
        $board->last_turn = $this->getTableDrTurns()->getLastTurn($game->id);
        
        //generate options for the player who's turn it is
        if ($currentUser!=null && $board->last_turn->user_id == $currentUser->id && $board->last_turn-> position > 0) {
            $board->nextTurn = new Entity();
            if (!$board->last_turn->returning) {
                $board->nextTurn->askReturn = true;
            }
            if ($this->getTableDrTurns()->canTakeTreasure($board)) {
                $board->nextTurn->askTaking = true;
            }
            if ($this->DrTurns->canDropTreasure($board)) {
                $board->nextTurn->askDropping = $playersTokens[$currentUser->id][2];
            //always ask about turn finish, no need to add it here
            }
        }
        
        return $board;
    }
}
