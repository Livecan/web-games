<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrowningGame $game
 */
?>
<div class="row">
    <div id="ocean">
        <?php foreach ($oceanBoard as $depth): ?>
        <div class="depth">
            <?php if ($depth->diver != null) { ?>
                <div class="diver">
                    <img src="img_trans.gif" class="diver-red" /><!--TODO: diver classes-->
                </div>
            <?php } ?>
            <div class="tokens">
                <?php foreach ($depth->tokens as $token): ?>
                <div class="token">
                    <img src="img_trans.gif" class="T1" /><!--TODO: token classes-->
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
