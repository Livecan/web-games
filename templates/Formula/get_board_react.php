<?php
    $this->Html->css('formula-game/formula-game', ['block' => true]);
    $this->Html->script('https://unpkg.com/react@17/umd/react.development.js', ['block' => true]);
    $this->Html->script('https://unpkg.com/react-dom@17/umd/react-dom.development.js', ['block' => true]);
    $this->Html->script('/js/jQueryRotate.js', ['block' => true]);
?>

<div id="root" style="width: 100%; height: 60vw; position: relative">
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
    $this->Html->script('/formula/js/app.js');
?>
</div>