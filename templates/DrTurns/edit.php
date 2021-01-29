<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrTurn $drTurn
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $drTurn->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $drTurn->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Dr Turns'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="drTurns form content">
            <?= $this->Form->create($drTurn) ?>
            <fieldset>
                <legend><?= __('Edit Dr Turn') ?></legend>
                <?php
                    echo $this->Form->control('game_id', ['options' => $games]);
                    echo $this->Form->control('user_id');
                    echo $this->Form->control('position');
                    echo $this->Form->control('round');
                    echo $this->Form->control('roll');
                    echo $this->Form->control('returning');
                    echo $this->Form->control('taking');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
