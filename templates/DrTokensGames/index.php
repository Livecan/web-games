<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrTokensGame[]|\Cake\Collection\CollectionInterface $drTokensGames
 */
?>
<div class="drTokensGames index content">
    <?= $this->Html->link(__('New Dr Tokens Game'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Dr Tokens Games') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('game_id') ?></th>
                    <th><?= $this->Paginator->sort('dr_token_id') ?></th>
                    <th><?= $this->Paginator->sort('position') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($drTokensGames as $drTokensGame): ?>
                <tr>
                    <td><?= $this->Number->format($drTokensGame->id) ?></td>
                    <td><?= $drTokensGame->has('game') ? $this->Html->link($drTokensGame->game->id, ['controller' => 'Games', 'action' => 'view', $drTokensGame->game->id]) : '' ?></td>
                    <td><?= $drTokensGame->has('dr_token') ? $this->Html->link($drTokensGame->dr_token->id, ['controller' => 'DrTokens', 'action' => 'view', $drTokensGame->dr_token->id]) : '' ?></td>
                    <td><?= $this->Number->format($drTokensGame->position) ?></td>
                    <td><?= $drTokensGame->has('user') ? $this->Html->link($drTokensGame->user->name, ['controller' => 'Users', 'action' => 'view', $drTokensGame->user->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $drTokensGame->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $drTokensGame->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $drTokensGame->id], ['confirm' => __('Are you sure you want to delete # {0}?', $drTokensGame->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
