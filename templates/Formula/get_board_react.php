<?php
    $this->Html->css('formula-game/formula-game', ['block' => true]);
    $this->Html->script('https://unpkg.com/react@17/umd/react.development.js', ['block' => true]);
    $this->Html->script('https://unpkg.com/react-dom@17/umd/react-dom.development.js', ['block' => true]);
    $this->Html->script('/js/jQueryRotate.js', ['block' => true]);
?>
<div id="root">
    
<?=
    $this->Html->script('/formula/js/app.js');
?>
</div>