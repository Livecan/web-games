<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrowningGame $game
 */

$this->Html->css('drowning-game/board', ['block' => true]);
?>
<div id="game_id" hidden="true">$game->id</div>
<div id="oxygen">
</div>
<div id="ocean">
    <?php for ($i = 1; $i <= 20; $i++): ?>
    <div id="depth<?= $i ?>" class="depth">
        <div class="tokens">
        </div>
    </div>
    <?php endfor; ?>
</div>
<div id="users">
</div>
<div id="nextTurn">
</div>
<script>
    $(document).ready(function(){
        $("button").click(function() {
            $.getJSON('<?= \Cake\Routing\Router::url(['action' => 'update-board-json', $game->id]) ?>',
                    function(data, status){
                        alert("Status: " + status + "\nData: " + JSON.stringify(data));
                        for (let depthNo in data["depths"]) {
                            let jsonTokens = data["depths"][depthNo].tokens;
                            let tokens = "";
                            for (let token in jsonTokens) {
                                tokens += '<div class="token T' + jsonTokens[token]["type"] + '"></div>';
                            }
                            $("#depth" + depthNo + " .tokens").html(tokens);
                        }
            });
        });
    });
</script>
<button>Get Current Board!</button>