<?php
    //TODO: use reference to web for the following react js files
    $this->Html->script('/js/react.development.js', ['block' => true]);
    $this->Html->script('/js/react-dom.development.js', ['block' => true]);
?>
<div id="root">
    <!--script>
        var gameTypeId = 2; //Formula Game
        var gameStateId = 1;    //Initial state
    </script-->
    <?= $this->Html->script('gameIndex.js', ['type' => 'module']); ?>
</div>
