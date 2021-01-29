<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Game $game
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Game'), ['action' => 'edit', $game->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Game'), ['action' => 'delete', $game->id], ['confirm' => __('Are you sure you want to delete # {0}?', $game->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Games'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Game'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="games view content">
            <h3><?= h($game->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($game->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($game->id) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Type') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($game->type)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Dr Tokens') ?></h4>
                <?php if (!empty($game->dr_tokens)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Value') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($game->dr_tokens as $drTokens) : ?>
                        <tr>
                            <td><?= h($drTokens->id) ?></td>
                            <td><?= h($drTokens->type) ?></td>
                            <td><?= h($drTokens->value) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'DrTokens', 'action' => 'view', $drTokens->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'DrTokens', 'action' => 'edit', $drTokens->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'DrTokens', 'action' => 'delete', $drTokens->id], ['confirm' => __('Are you sure you want to delete # {0}?', $drTokens->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Dr Turns') ?></h4>
                <?php if (!empty($game->dr_turns)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Game Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Position') ?></th>
                            <th><?= __('Round') ?></th>
                            <th><?= __('Roll') ?></th>
                            <th><?= __('Returning') ?></th>
                            <th><?= __('Taking') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($game->dr_turns as $drTurns) : ?>
                        <tr>
                            <td><?= h($drTurns->id) ?></td>
                            <td><?= h($drTurns->game_id) ?></td>
                            <td><?= h($drTurns->user_id) ?></td>
                            <td><?= h($drTurns->position) ?></td>
                            <td><?= h($drTurns->round) ?></td>
                            <td><?= h($drTurns->roll) ?></td>
                            <td><?= h($drTurns->returning) ?></td>
                            <td><?= h($drTurns->taking) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'DrTurns', 'action' => 'view', $drTurns->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'DrTurns', 'action' => 'edit', $drTurns->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'DrTurns', 'action' => 'delete', $drTurns->id], ['confirm' => __('Are you sure you want to delete # {0}?', $drTurns->id)]) ?>
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
