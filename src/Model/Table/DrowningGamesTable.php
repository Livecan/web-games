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
    
    public function start($game) {
        
        //firstly randomize player order
        usort($game->users, function($_a, $_b) { return rand(-1, 1); });
        for ($i = 0; $i < count($game->users); $i++) {
            $game->users[$i]->_joinData->order_number = $i;
        }
        $game->setDirty('users', true);
        //debug($game->users);
        
        //secondly randomly deal tokens
        $tokens = $this->DrTokens->find('all')->toArray();
        usort($tokens,
                function($_a, $_b) {
                    return $_a->type == $_b->type ?
                        rand(-1, 1) : $_a->type > $_b->type;
                });
        $game->dr_tokens = $tokens;
        $drTokensGames = $this->getTableLocator()->get('DrTokensGames');
        for ($i = 0; $i < count($tokens); $i++) {
            $game->dr_tokens[$i]->_joinData =
                $drTokensGames->newEntity(['position' => $i]);
        }
        
        //thirdly change game state to started
        $game->game_state_id = 2;
        //debug($game);
        
        $result = $this->save($game, ['associated' => ['Users', 'DrTokens']]);
        
        if (!$result) {
            return false;
        }
        
        return true;
    }
    
    private $maxDepth = 20; //TODO: refactor
    private $maxOxygen = 25;    //TODO: refactor
    
    public function getBoard($game, $user = null) {
        $board = new Entity();
        
        $board->depths = [];

        for ($depth = 1; $depth <= $this->maxDepth; $depth++) {
            $board->depths[$depth] = new Entity();
        }
        
        //order dr_turns by ->created and then keep only the last for each player
        usort($game->dr_turns, function($_before, $_after) {    //TODO: these might be written more nice?
            return $_before->created < $_after-created ? 1 : -1; });
        $last_turns = array_slice($game->dr_turns, -count($game->users),
            count($game->users));
        foreach ($last_turns as $_turn) {
            $board->depths[$_turn->position]->diver = array_filter($game->users,
                function($_user) use ($_turn) {
                    return $_user->id == $_turn->user_id; })[0]->
                        _joinData->order;
        }

        //assign tokens to different depths
        for ($depth = 1; $depth <= $this->maxDepth; $depth++) { //TODO: these might be written more nice?
            $board->depths[$depth]->tokens = array_filter($game->dr_tokens,
                function($_token) use ($depth) {
                    return $_token->_joinData->position == $depth; });
        }
        
        $board->users = $game->users;
        usort($board->users, function($_before, $_after) {
            return $_before->_joinData->order < $_after->_joinData->order ?
                1 : -1; });
        
        if (count($last_turns) > 0) {
            $last_turns[count($last_turns) - 1];    //TODO: this is the last turn - get oxygen level from it and include it in the return value
        } else {
            $board->oxygen = $this->maxOxygen;
        }
        //TODO: return possible moves for the player - add method parameter - need to know whether the requesting player is the player whose turn it is

        return $board;
    }
}
