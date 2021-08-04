<?php
    $this->Html->css('formula-game/formula-setup', ['block' => true]);
    //TODO: use reference to web for the following react js files
    $this->Html->script('/js/react.development.js', ['block' => true]);
    $this->Html->script('/js/react-dom.development.js', ['block' => true]);
?>

<div id="root" style="width: 100%; height: 85vh; position: relative">
    <script>
        var id = <?= $formulaGame->id ?>;
    </script>
<?=
    $this->Html->script('formulaSetup.js', ['type' => 'module']);
?>
</div>
