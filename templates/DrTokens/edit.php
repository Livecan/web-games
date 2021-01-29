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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $drToken->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $drToken->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Dr Tokens'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="drTokens form content">
            <?= $this->Form->create($drToken) ?>
            <fieldset>
                <legend><?= __('Edit Dr Token') ?></legend>
                <?php
                    echo $this->Form->control('type');
                    echo $this->Form->control('value');
                    echo $this->Form->control('games._ids', ['options' => $games]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
