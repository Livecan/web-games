<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GamesUser $gamesUser
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $gamesUser->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $gamesUser->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Games Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="gamesUsers form content">
            <?= $this->Form->create($gamesUser) ?>
            <fieldset>
                <legend><?= __('Edit Games User') ?></legend>
                <?php
                    echo $this->Form->control('game_id', ['options' => $games]);
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('order_number');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
