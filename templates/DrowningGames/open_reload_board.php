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
    <div id="outDivers">
    </div>
</div>
<div id="users">
</div>
<div id="nextTurn">
</div>
<script>
    var drowningGameFillBoard = function(depths) {
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
                let diverUserId = depths[depthNo].diver["id"];
                $("#depth" + depthNo).
                        append('<div class="diver"/><span class="user_id" hidden="true">' +
                            diverUserId + '</span></div>');
            }
        }
    }
    
    var drowningGameFillUsers = function(users) {
        let userElements = "";
        for (let userIndex in users) {
            let user = users[userIndex];
            userElements = "<div class=\"user\">";
            userElements += '<span class="id">' + user["id"] + '</span>';
            userElements += '<span class="name">' + user["name"] + '</span>';
            userElements += '<span class="order_number">' + user["order_number"] + '</span>';
            userElements += "</div>";
        }
        $("#users").append(userElements);
    }
    
    var drowningGameFillOutDivers = function(outDivers) {
        let outDiversElements = "";
        for (let outDiverIndex in outDivers) {
            outDiversElements += '<span class="id">' + outDivers[outDiverIndex]["id"] + '</span>';
        }
        $("#outDivers").append(outDiversElements);
    }
    
    $(document).ready(function(){
        //$("button").click(function() {
            $.getJSON('<?= \Cake\Routing\Router::url(['action' => 'update-board-json', $game->id]) ?>',
                    function(data, status){
                        alert(Object.keys(data));
                        drowningGameFillBoard(data["depths"]);
                        $("#modified").html(data["modified"]);
                        drowningGameFillUsers(data["users"]);
                        drowningGameFillOutDivers(data["outDivers"]);
                        $("#oxygen").html(data["oxygen"]);
                        //TODO: next turns buttons
            });
        //});
    });
</script>
<!--button>Get Current Board!</button-->