<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GamesUser[]|\Cake\Collection\CollectionInterface $gamesUsers
 */
?>
<div class="gamesUsers index content">
    <?= $this->Html->link(__('New Games User'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Games Users') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('game_id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('order_number') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gamesUsers as $gamesUser): ?>
                <tr>
                    <td><?= $this->Number->format($gamesUser->id) ?></td>
                    <td><?= $gamesUser->has('game') ? $this->Html->link($gamesUser->game->id, ['controller' => 'Games', 'action' => 'view', $gamesUser->game->id]) : '' ?></td>
                    <td><?= $gamesUser->has('user') ? $this->Html->link($gamesUser->user->name, ['controller' => 'Users', 'action' => 'view', $gamesUser->user->id]) : '' ?></td>
                    <td><?= $this->Number->format($gamesUser->order_number) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $gamesUser->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $gamesUser->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $gamesUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $gamesUser->id)]) ?>
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
