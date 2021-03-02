<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrowningGame $game
 */

$this->Html->css('drowning-game/board', ['block' => true]);
?>
<div id="game_id" hidden="true"><?= $game->id ?></div>
<div id="modified" hidden="true"></div>
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
    var drowningGameFillBoard = function(depths, users) {
        for (let depthNo in depths) {
            let jsonTokens = depths[depthNo].tokens;
            let tokens = "";
            for (let token in jsonTokens) {
                tokens += '<div class="token T' + jsonTokens[token]["type"] + '"></div>';
            }
            if (tokens != "") {
                $("#depth" + depthNo + " .tokens").html(tokens);
            } else {
                $("#depth" + depthNo + " .tokens").html('<img src="/img/drowning-game/redX2.png"></img>');
            }
            if (depths[depthNo].diver != undefined) {
                $("#depth" + depthNo).
                        append('<div class="diver D0" /><span class="user_id" hidden="true">' + //TODO: DX - insert order number? and include user color later
                            depths[depthNo].diver["id"] + '</span></div>');
            }
        }
    }
    $(document).ready(function(){
        //$("button").click(function() {
            $.getJSON('<?= \Cake\Routing\Router::url(['action' => 'update-board-json', $game->id]) ?>',
                    function(data, status){
                        drowningGameFillBoard(data["depths"], data["users"]);
                        $("#modified").html(data["modified"]);
            });
        //});
    });
</script>
<!--button>Get Current Board!</button-->