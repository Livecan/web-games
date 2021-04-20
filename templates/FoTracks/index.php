<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FoTrack[]|\Cake\Collection\CollectionInterface $foTracks
 */
?>
<div class="foTracks index content">
    <?= $this->Html->link(__('New Fo Track'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Fo Tracks') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('game_plan') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($foTracks as $foTrack): ?>
                <tr>
                    <td><?= $this->Number->format($foTrack->id) ?></td>
                    <td><?= h($foTrack->name) ?></td>
                    <td><?= h($foTrack->game_plan) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $foTrack->id]) ?>
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
