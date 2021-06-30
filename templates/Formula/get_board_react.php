<?php
    $this->Html->css('formula-game/formula-game', ['block' => true]);
    //TODO: use reference to web for the following react js files
    $this->Html->script('/js/react.development.js', ['block' => true]);
    $this->Html->script('/js/react-dom.development.js', ['block' => true]);
?>

<div id="root" style="width: 100%; height: 85vh; position: relative">
    <script>
        var id = <?= $formulaGame->id ?>;
        var gameBoard = "/img/formula/<?= $formulaGame->fo_game->fo_track->game_plan ?>";
        var positions = {
        <?php foreach ($formulaGame->fo_game->fo_track->fo_positions as $foPosition) : ?>
            <?= '"' . $foPosition->id . '": { x: "' . $foPosition->pos_x . '", y: "' .
                $foPosition->pos_y . '", angle: "' . $foPosition->angle . '"}, ' ?>
        <?php endforeach; ?>
        };
    </script>
<?=
    $this->Html->script('/jsx/formula/app.js');
?>
</div>