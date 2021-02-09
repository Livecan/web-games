<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrowningGame $game
 */
?>
<div id="ocean">
    <div class="oxygen">
        <?= h($board->oxygen) ?>
    </div>
    <?php foreach ($board->depths as $depth): ?>
    <div class="depth">
        <?php if ($depth->diver != null): ?>
            <div class="diver">
                <img src="img_trans.gif" class="<?= h('D' . $depth->diver) ?>" /><!--TODO: diver classes-->
            </div>
        <?php endif; ?>
        <div class="tokens">
            <?php foreach ($depth->tokens as $token): ?>
            <div class="token">
                <img src="img_trans.gif" class="<?= h('T' . $token->type) ?>" /><!--TODO: token classes-->
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div id="users">
    <?php foreach ($board->users as $user): ?>
    <div class="user <?= h('D' . $user->_joinData->order) ?>">
        <?= h($user->name) ?>
    </div>
    <?php endforeach; ?>
</div>
<?php if ($board->nextTurn): ?>
<div class="nextTurn">
    <?php if ($board->nextTurn->askReturn): ?>
        <!--TODO: insert button for return--><p>askReturn</p>
    <?php endif; ?>
    <?php if ($board->nextTurn->askTaking): ?>
        <!--TODO: insert button for taking--><p>askTaking</p>
    <?php endif; ?>
    <!--TODO: insert button for finish--><p>askFinish</p>
</div>
<?php endif; ?>