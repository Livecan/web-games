<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrTokensGame $drTokensGame
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Dr Tokens Games'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="drTokensGames form content">
            <?= $this->Form->create($drTokensGame) ?>
            <fieldset>
                <legend><?= __('Add Dr Tokens Game') ?></legend>
                <?php
                    echo $this->Form->control('game_id', ['options' => $games]);
                    echo $this->Form->control('dr_token_id', ['options' => $drTokens]);
                    echo $this->Form->control('position');
                    echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
