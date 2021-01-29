<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrToken[]|\Cake\Collection\CollectionInterface $drTokens
 */
?>
<div class="drTokens index content">
    <?= $this->Html->link(__('New Dr Token'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Dr Tokens') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('type') ?></th>
                    <th><?= $this->Paginator->sort('value') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($drTokens as $drToken): ?>
                <tr>
                    <td><?= $this->Number->format($drToken->id) ?></td>
                    <td><?= $this->Number->format($drToken->type) ?></td>
                    <td><?= $this->Number->format($drToken->value) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $drToken->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $drToken->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $drToken->id], ['confirm' => __('Are you sure you want to delete # {0}?', $drToken->id)]) ?>
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
