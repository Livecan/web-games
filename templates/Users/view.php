<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users view content">
            <h3><?= h($user->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Password') ?></th>
                    <td><?= h($user->password) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($user->id) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Name') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($user->name)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Games') ?></h4>
                <?php if (!empty($user->games)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Type') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->games as $games) : ?>
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
            <div class="related">
                <h4><?= __('Related Dr Tokens Games') ?></h4>
                <?php if (!empty($user->dr_tokens_games)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Game Id') ?></th>
                            <th><?= __('Dr Token Id') ?></th>
                            <th><?= __('Position') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($user->dr_tokens_games as $drTokensGames) : ?>
                        <tr>
                            <td><?= h($drTokensGames->id) ?></td>
                            <td><?= h($drTokensGames->game_id) ?></td>
                            <td><?= h($drTokensGames->dr_token_id) ?></td>
                            <td><?= h($drTokensGames->position) ?></td>
                            <td><?= h($drTokensGames->user_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'DrTokensGames', 'action' => 'view', $drTokensGames->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'DrTokensGames', 'action' => 'edit', $drTokensGames->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'DrTokensGames', 'action' => 'delete', $drTokensGames->id], ['confirm' => __('Are you sure you want to delete # {0}?', $drTokensGames->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Dr Turns') ?></h4>
                <?php if (!empty($user->dr_turns)) : ?>
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
                        <?php foreach ($user->dr_turns as $drTurns) : ?>
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
