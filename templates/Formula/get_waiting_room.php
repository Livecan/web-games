<?php

?>
<button onclick="foSetupReloadBoard()">Refresh Setup</button>
<script>
    var modified;
    var foSetupReloadBoard = function() {
        let url = '<?= \Cake\Routing\Router::url(
                ['action' => 'getSetupUpdateJson', $formulaGame->id]) ?>';
        $.getJSON(url, {modified: modified}, function(data) {
            
        });
    }
</script>