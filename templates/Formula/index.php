<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Game[]|\Cake\Collection\CollectionInterface $games
 */
?>
<div class="games index content">
    <!--?= $this->Html->link(__('New Game'), ['action' => 'add'], ['class' => 'button float-right']) ?-->
    <h3><?= __('Games') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($formulaGames as $formulaGame): ?>
                <tr>
                    <td><?= $this->Number->format($formulaGame->id) ?></td>
                    <td><?= h($formulaGame->name) ?></td>
                    <td class="actions">
                        <!--?= $this->Html->link(__('View'), ['action' => 'view', $formulaGame->id]) ?-->
                        <!--?= $this->Html->link(__('Edit'), ['action' => 'edit', $formulaGame->id]) ?-->
                        <!--?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $formulaGame->id], ['confirm' => __('Are you sure you want to delete # {0}?', $formulaGame->id)]) ?-->
                        <?= $this->Form->postLink(__('Join'), ['action' => 'joinGame', $formulaGame->id]) ?>
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
