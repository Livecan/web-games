<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrTurn[]|\Cake\Collection\CollectionInterface $drTurns
 */
?>
<div class="drTurns index content">
    <?= $this->Html->link(__('New Dr Turn'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Dr Turns') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('game_id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('position') ?></th>
                    <th><?= $this->Paginator->sort('round') ?></th>
                    <th><?= $this->Paginator->sort('roll') ?></th>
                    <th><?= $this->Paginator->sort('returning') ?></th>
                    <th><?= $this->Paginator->sort('taking') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($drTurns as $drTurn): ?>
                <tr>
                    <td><?= $this->Number->format($drTurn->id) ?></td>
                    <td><?= $drTurn->has('game') ? $this->Html->link($drTurn->game->id, ['controller' => 'Games', 'action' => 'view', $drTurn->game->id]) : '' ?></td>
                    <td><?= $this->Number->format($drTurn->user_id) ?></td>
                    <td><?= $this->Number->format($drTurn->position) ?></td>
                    <td><?= $this->Number->format($drTurn->round) ?></td>
                    <td><?= $this->Number->format($drTurn->roll) ?></td>
                    <td><?= h($drTurn->returning) ?></td>
                    <td><?= h($drTurn->taking) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $drTurn->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $drTurn->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $drTurn->id], ['confirm' => __('Are you sure you want to delete # {0}?', $drTurn->id)]) ?>
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
