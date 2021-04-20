<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FoTrack $foTrack
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Fo Tracks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="foTracks view content">
            <h3><?= h($foTrack->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($foTrack->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Game Plan') ?></th>
                    <td><?= h($foTrack->game_plan) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Fo Curves') ?></h4>
                <?php if (!empty($foTrack->fo_curves)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Stops') ?></th>
                            <th><?= __('Name') ?></th>
                        </tr>
                        <?php foreach ($foTrack->fo_curves as $foCurves) : ?>
                        <tr>
                            <td><?= h($foCurves->stops) ?></td>
                            <td><?= h($foCurves->name) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
