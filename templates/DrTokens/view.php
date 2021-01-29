<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrToken $drToken
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Dr Token'), ['action' => 'edit', $drToken->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Dr Token'), ['action' => 'delete', $drToken->id], ['confirm' => __('Are you sure you want to delete # {0}?', $drToken->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Dr Tokens'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Dr Token'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="drTokens view content">
            <h3><?= h($drToken->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($drToken->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Type') ?></th>
                    <td><?= $this->Number->format($drToken->type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Value') ?></th>
                    <td><?= $this->Number->format($drToken->value) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Games') ?></h4>
                <?php if (!empty($drToken->games)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Type') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($drToken->games as $games) : ?>
                        <tr>
                            <td><?= h($games->id) ?></td>
                            <td><?= h($games->name) ?></td>
                            <td><?= h($games->type) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Games', 'action' => 'view', $games->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Games', 'action' => 'edit', $games->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Games', 'action' => 'delete', $games->id], ['confirm' => __('Are you sure you want to delete # {0}?', $games->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
