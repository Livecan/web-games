<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrTokensGame $drTokensGame
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Dr Tokens Game'), ['action' => 'edit', $drTokensGame->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Dr Tokens Game'), ['action' => 'delete', $drTokensGame->id], ['confirm' => __('Are you sure you want to delete # {0}?', $drTokensGame->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Dr Tokens Games'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Dr Tokens Game'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="drTokensGames view content">
            <h3><?= h($drTokensGame->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Game') ?></th>
                    <td><?= $drTokensGame->has('game') ? $this->Html->link($drTokensGame->game->id, ['controller' => 'Games', 'action' => 'view', $drTokensGame->game->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Dr Token') ?></th>
                    <td><?= $drTokensGame->has('dr_token') ? $this->Html->link($drTokensGame->dr_token->id, ['controller' => 'DrTokens', 'action' => 'view', $drTokensGame->dr_token->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $drTokensGame->has('user') ? $this->Html->link($drTokensGame->user->name, ['controller' => 'Users', 'action' => 'view', $drTokensGame->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($drTokensGame->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Position') ?></th>
                    <td><?= $this->Number->format($drTokensGame->position) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
