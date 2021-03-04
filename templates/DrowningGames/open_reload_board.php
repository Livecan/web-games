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
    };
    
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
    };
    
    var drowningGameFillOutDivers = function(outDivers) {
        let outDiversElements = "";
        for (let outDiverIndex in outDivers) {
            outDiversElements += '<span class="id">' + outDivers[outDiverIndex]["id"] + '</span>';
        }
        $("#outDivers").append(outDiversElements);
    };
    
    var drowningGameFillNextTurn = function(nextTurn) {
        let nextTurnElement = $("#nextTurn");
        nextTurnElement.empty();
        if (nextTurn["askTaking"]) {
            nextTurnElement.append("<button id=\"askTakingBtn\" >Take</button>");
            $("#askTakingBtn").click(function() {
                $.post('<?= \Cake\Routing\Router::url(['controller' => 'DrowningGames', 'action' => 'processActions', $game->id]) ?>',
                { _csrfToken: csrfToken, game_id: <?= $game->id ?>, taking: true });
            });
        }
        if (nextTurn["askReturn"]) {
            nextTurnElement.append("<button id=\"askReturnBtn\" >Return</button>");
            $("#askReturnBtn").click(function() {
                $.post('<?= \Cake\Routing\Router::url(['controller' => 'DrowningGames', 'action' => 'processActions', $game->id]) ?>',
                { _csrfToken: csrfToken, game_id: <?= $game->id ?>, start_returning: true });
            });
        }
        //TODO: dropping is a bit more complicated, do next commit
        nextTurnElement.append("<button id=\"finishBtn\">Finish turn</button>");
        $("#finishBtn").click(function() {
            $.post('<?= \Cake\Routing\Router::url(['controller' => 'DrowningGames', 'action' => 'processActions', $game->id]) ?>',
                { _csrfToken: csrfToken, game_id: <?= $game->id ?>, finish: true })
        });
    };
    
    $(document).ready(function(){
        //$("button").click(function() {
            $.getJSON('<?= \Cake\Routing\Router::url(['action' => 'update-board-json', $game->id]) ?>',
                    function(data, status){
                        //alert(Object.keys(data));
                        drowningGameFillBoard(data["depths"]);
                        $("#modified").html(data["modified"]);
                        drowningGameFillUsers(data["users"]);
                        drowningGameFillOutDivers(data["outDivers"]);
                        $("#oxygen").html(data["oxygen"]);
                        
                        drowningGameFillNextTurn(data["nextTurn"]);
            });
        //});
    });
</script>
<!--button>Get Current Board!</button-->