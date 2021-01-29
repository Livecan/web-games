<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GamesUser $gamesUser
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Games User'), ['action' => 'edit', $gamesUser->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Games User'), ['action' => 'delete', $gamesUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $gamesUser->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Games Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Games User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="gamesUsers view content">
            <h3><?= h($gamesUser->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Game') ?></th>
                    <td><?= $gamesUser->has('game') ? $this->Html->link($gamesUser->game->id, ['controller' => 'Games', 'action' => 'view', $gamesUser->game->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $gamesUser->has('user') ? $this->Html->link($gamesUser->user->name, ['controller' => 'Users', 'action' => 'view', $gamesUser->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($gamesUser->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Order Number') ?></th>
                    <td><?= $this->Number->format($gamesUser->order_number) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
