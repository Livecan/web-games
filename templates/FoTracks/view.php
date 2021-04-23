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
            <div>
                <div style="float: left;">
                    <input type="checkbox" checked="true" onchange="display('left', this.checked)">left</input>
                    <input type="checkbox" checked="true" onchange="display('straight', this.checked)">straight</input>
                    <input type="checkbox" checked="true" onchange="display('right', this.checked)">right</input>
                    <input type="checkbox" checked="true" onchange="display('curve', this.checked)">curve</input>
                    <!--input type="checkbox" checked="true">adjacent</input-->
                    <!--input type="checkbox" checked="false">same distance</input-->
                </div>
                <div style="float: right;">
                    <button onclick="zoom(true)">+</button>
                    <button onclick="zoom(false)">-</button>
                </div>
<script>
    var trackZooms = ["100%", "150%", "200%", "300%", "400%"];
    var trackZoomsIndex = 0;
    var zoom = function(zoomIn) {
        if (zoomIn) {
            trackZoomsIndex = Math.min(trackZoomsIndex + 1, trackZooms.length - 1);
        } else {
            trackZoomsIndex = Math.max(trackZoomsIndex - 1, 0);
        }
        $("#track").css("width", trackZooms[trackZoomsIndex]);
    }
    var display = function(type, value) {
        alert(type);
        alert(value);
        let lines;
        switch (type) {
            case ("left"):
                lines = $("#track .left");
                break;
            case ("straight"):
                lines = $("#track .straight");
                break;
            case ("right"):
                lines = $("#track .right");
                break;
            case ("curve"):
                lines = $("#track .curve");
                break;
            case ("pitlane_move"):
                lines = $("#track .pitlane_move");
                break;
        }

        lines.css("display", value ? "inline" : "none");
    }
</script>
            </div>
            <div style="width: 100%; max-height: 600px; overflow: auto;">
                <div id="track" style="position: relative;">
                    <img src="<?= "/img/formula/" . $foTrack->game_plan ?>" width= "200%" />
                    <svg style="position:absolute; left: 0; top: 0; width: 100%; height: 100%;">
                    <?php foreach ($foTrack->fo_positions as $foPosition) : ?>
                        <?php foreach ($foPosition->fo_position2_positions_from as $foPosition2Position) :
                            $class = "";
                            $color;
                            $isMove = false;
                            if ($foPosition2Position->is_left) {
                                $class .= "left ";
                                $color = "red";
                                $isMove = true;
                            }
                            if ($foPosition2Position->is_straight) {
                                $class .= "straight ";
                                $color = "green";
                                $isMove = true;
                            }
                            if ($foPosition2Position->is_right) {
                                $class .= "right ";
                                $color = "blue";
                                $isMove = true;
                            }
                            if ($foPosition2Position->is_curve) {
                                $class .= "curve ";
                                $color = "white";
                                $isMove = true;
                            }
                            if ($foPosition2Position->is_adjacent) {
                                $class .= "adjacent ";
                            }
                            if ($foPosition2Position->is_equal_distance) {
                                $class .= "equal_distance ";
                            }
                            if ($foPosition2Position->is_pitlane_move) {
                                $class .= "pitlane_move ";
                                $color = "black";
                                $isMove = true;
                            }
                            if (!$isMove) {
                                continue;
                            }
                            ?>
                        <line x1="<?= $foPosition->pos_x / 1000 ?>%"
                              y1="<?= $foPosition->pos_y / 1000 ?>%"
                              x2="<?= $foPosition2Position->fo_position_to->pos_x / 1000 ?>%"
                              y2="<?= $foPosition2Position->fo_position_to->pos_y / 1000 ?>%"
                              class="<?= $class ?>"
                              style="stroke: <?= $color ?>/*rgb(255,0,0);*/; stroke-width: 1px;" />
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    </svg>
                </div>
            </div>
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