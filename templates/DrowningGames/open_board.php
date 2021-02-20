<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrowningGame $game
 */

$this->Html->css('drowning-game/board', ['block' => true]);
?>
<div class="oxygen">
    <?= h($board->oxygen) ?>
</div>
<div id="ocean">
    <?php foreach ($board->depths as $depth): ?>
    <div class="depth">
        <?php if ($depth->diver != null): ?>
            <div class="diver <?= h('D' . $depth->diver->order_number)?>">
                <?= $this->Html->image('drowning-game/img_trans.gif', ['alt' => 'diver']); ?>
            </div>
        <?php endif; ?>
        <div class="tokens">
            <?php foreach ($depth->tokens as $token): ?>
            <div class="token <?= h('T' . $token->type) ?>">
                <?= $this->Html->image('drowning-game/img_trans.gif', ['alt' => 'token']); ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div id="users">
    <?php foreach ($board->users as $user): ?>
    <div class="user <?= h('D' . $user->_joinData->order) ?>">
        <?= h($user->name) ?>
    </div>
    <?php endforeach; ?>
</div>
<?php if ($board->nextTurn): ?>
<div class="nextTurn">
    <?php if ($board->nextTurn->askReturn): ?>
    <div class="nextTurnButton">
        <?= $this->Form->postLink(__('Start returning'),
            ['controller' => 'DrowningGames', 'action' => 'processActions', $board->id],
            ['data' => ['game_id' =>$board->id, 'turn_id' => $board->last_turn->id,
                'start_returning' => true]]) ?>
    </div>
    <?php endif; ?>
    <?php if ($board->nextTurn->askTaking): ?>
    <div class="nextTurnButton">
        <?= $this->Form->postLink(__('Take treasure'),
            ['controller' => 'DrowningGames', 'action' => 'processActions', $board->id],
            ['data' => ['game_id' =>$board->id, 'turn_id' => $board->last_turn->id,
                'taking' => true]]) ?>
    </div>
    <?php endif; ?>
    <?php if ($board->nextTurn->askDropping): ?>
    <?php foreach ($board->nextTurn->askDropping as $_droppableTreasure): ?>
    <div class="nextTurnButton">
        <?= $this->Form->postLink(__('Drop treasures ') . implode(", ", $_droppableTreasure),   //TODO: display the treasures properly and insert group_number in the output
            ['controller' => 'DrowningGames', 'action' => 'processActions', $board->id],
            ['data' => ['game_id' =>$board->id, 'turn_id' => $board->last_turn->id,
                'dropping' => true]]) ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    <div class="nextTurnButton">
        <?= $this->Form->postLink(__('Finish turn'),
            ['controller' => 'DrowningGames', 'action' => 'processActions', $board->id],
            ['data' => ['game_id' =>$board->id, 'turn_id' => $board->last_turn->id,
                'finish' => true]]) ?>
    </div>
</div>
<?php endif; ?>