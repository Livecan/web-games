<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrTurn $drTurn
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Dr Turn'), ['action' => 'edit', $drTurn->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Dr Turn'), ['action' => 'delete', $drTurn->id], ['confirm' => __('Are you sure you want to delete # {0}?', $drTurn->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Dr Turns'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Dr Turn'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="drTurns view content">
            <h3><?= h($drTurn->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Game') ?></th>
                    <td><?= $drTurn->has('game') ? $this->Html->link($drTurn->game->id, ['controller' => 'Games', 'action' => 'view', $drTurn->game->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($drTurn->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('User Id') ?></th>
                    <td><?= $this->Number->format($drTurn->user_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Position') ?></th>
                    <td><?= $this->Number->format($drTurn->position) ?></td>
                </tr>
                <tr>
                    <th><?= __('Round') ?></th>
                    <td><?= $this->Number->format($drTurn->round) ?></td>
                </tr>
                <tr>
                    <th><?= __('Roll') ?></th>
                    <td><?= $this->Number->format($drTurn->roll) ?></td>
                </tr>
                <tr>
                    <th><?= __('Returning') ?></th>
                    <td><?= $drTurn->returning ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Taking') ?></th>
                    <td><?= $drTurn->taking ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
