<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrowningGame $game
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?> //TODO</h4>
            <?= $this->Html->link(__('Edit Game'), ['action' => 'edit', $game->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Game'), ['action' => 'delete', $game->id], ['confirm' => __('Are you sure you want to delete # {0}?', $game->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Games'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Game'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="games view content">
            <h3><?= h($game->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($game->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($game->id) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Type') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($game->type)); ?>
                </blockquote>
            </div>
        </div>
    </div>
    <div id="board">
        <table>
        <?php
        usort($game->dr_turns, function($_turn_a, $_turn_b) { return $_turn_a->id < $_turn_b->id; });
        foreach ($game->users as $_user) {
            $_user->position = 0;
            foreach ($game->dr_turns as $_dr_turn) {
                if ($_dr_turn->user_id == $_user->id) {
                    $_user->position = $_dr_turn->position;
                }
            }
        }
        for ($position = 1; $position < 21; $position++) { ?>
            <tr>
                <td>
                    <?php
                        $playersAtCurrentPosition = array_filter($game->users, function($_user) use ($position) {
                            return $_user->position == $position; });
                        if (count($playersAtCurrentPosition) == 1) {
                            ?><?= __('Player ') . $playersAtCurrentPosition[0]->name ?><?php
                        }
?>
                </td>
                <?php
                $dr_positioned_tokens = array_filter($game->dr_tokens, 
                    function($_dr_token) use ($position) { return $_dr_token->_joinData->position == $position; });
                //debug($dr_positioned_tokens);
                foreach ($dr_positioned_tokens as $dr_token) { ?>
                <td>
                        <?=
                            __('Token type ') . $dr_token->type
                        ?>
                </td>
                        <?php
                    }
                ?>
            </tr>
        <?php }; ?>
        </table>
    </div>
</div>
