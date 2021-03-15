<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrowningGame $game
 */

$this->Html->css('drowning-game/board', ['block' => true]);
?>
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
    var drGameId;
    var drModified = null;
    var drTurnId = null;
    
    var drFillBoard = function(depths, users) {
        for (let depthNo in depths) {
            let jsonTokens = depths[depthNo].tokens;
            let tokens = "";
            for (let token in jsonTokens) {
                tokens += '<div class="token T' + jsonTokens[token]["type"] + '"></div>';
            }
            if (tokens !== "") {
                $("#depth" + depthNo + " .tokens").html(tokens);
            } else {
                let img = document.createElement("img");
                img.src = "/img/drowning-game/redX2.png";
                img.style= "position: absolute; width: 80%; height: 80%";
                $("#depth" + depthNo + " .tokens").html(img);
            }
            
            $("#depth" + depthNo + " .diver").remove();
            if (depths[depthNo].diver !== undefined) {
                let diverUserId = depths[depthNo].diver["id"];
                let user = users.find(function(_user) { return _user["id"] === diverUserId; });
                $("#depth" + depthNo).
                        append('<div class="diver D' + user["order_number"] + '"/><span class="user_id" hidden="true">' +
                            diverUserId + '</span></div>');
            }
        }
    };
    
    var drFillUsers = function(users) {
        let userElements = "";
        for (let userIndex in users) {
            let user = users[userIndex];
            userElements = "<div class=\"user\">";
            userElements += '<span class="id">' + user["id"] + '</span>';
            userElements += '<span class="name">' + user["name"] + '</span>';
            userElements += '<span class="order_number">' + user["order_number"] + '</span>';
            userElements += "</div>";
        }
        $("#users").html(userElements);
    };
    
    var drFillOutDivers = function(outDivers) {
        let outDiversElements = "";
        for (let outDiverIndex in outDivers) {
            outDiversElements += '<span class="id">' + outDivers[outDiverIndex]["id"] + '</span>';
        }
        $("#outDivers").html(outDiversElements);
    };
    
    var drFillNextTurn = function(nextTurn) {
        let nextTurnElement = $("#nextTurn");
        nextTurnElement.empty();
        if (nextTurn["askTaking"]) {
            nextTurnElement.append("<button id=\"askTakingBtn\" >Take</button>");
            $("#askTakingBtn").click(function() {
                $.post('<?= \Cake\Routing\Router::url(['controller' => 'DrowningGames', 'action' => 'processActions', $game->id]) ?>',
                    { _csrfToken: csrfToken, game_id: drGameId, taking: true, turn_id: drTurnId });
            });
        }
        
        if (nextTurn["askReturn"]) {
            nextTurnElement.append("<button id=\"askReturnBtn\" >Return</button>");
            $("#askReturnBtn").click(function() {
                $.post('<?= \Cake\Routing\Router::url(['controller' => 'DrowningGames', 'action' => 'processActions', $game->id]) ?>',
                    { _csrfToken: csrfToken, game_id: drGameId, start_returning: true, turn_id: drTurnId });
            });
        }
        
        if (nextTurn["askDropping"]) {
            dropSet = nextTurn["askDropping"];
            console.log(dropSet);
            for (let groupNumber in dropSet) {
                let droppingButton = document.createElement("button");
                droppingButton.setAttribute("class", "askDropBtn");
                groupTokens = dropSet[groupNumber];
                for (let groupToken of groupTokens) {
                    let groupTokenElement = document.createElement("div");
                    groupTokenElement.setAttribute("class", "token T" + groupToken["type"]);
                    droppingButton.appendChild(groupTokenElement);
                }
                nextTurnElement.append(droppingButton);
                droppingButton.onclick = function() {
                    $.post('<?= \Cake\Routing\Router::url(['controller' => 'DrowningGames', 'action' => 'processActions', $game->id]) ?>',
                        { _csrfToken: csrfToken, game_id: drGameId, dropping: true, group_number: groupNumber, turn_id: drTurnId });
                };
            }
        }
        
        nextTurnElement.append("<button id=\"finishBtn\">Finish turn</button>");
        $("#finishBtn").click(function() {
            $.post('<?= \Cake\Routing\Router::url(['controller' => 'DrowningGames', 'action' => 'processActions', $game->id]) ?>',
                { _csrfToken: csrfToken, game_id: drGameId, finish: true, turn_id: drTurnId });
        });
    };
    
    var drRefreshBoard = function() {
        let url = '<?= \Cake\Routing\Router::url(
                ['action' => 'update-board-json', $game->id]) ?>';
        $.getJSON(url, { modified: drModified },
                function(data, status){
                    if (data["hasUpdated"]) {
                        drGameId = data["id"];
                        drTurnId = data["last_turn"]["id"];
                        drModified = data["modified"];
                        $("#oxygen").html(data["oxygen"]);
                        drFillBoard(data["depths"], data["users"]);
                        drFillUsers(data["users"]);
                        drFillOutDivers(data["outDivers"]);
                        drFillNextTurn(data["nextTurn"]);
                    }
        }).always(function() {
            setTimeout(drRefreshBoard, 500);
        });
    }
    
    $(document).ready(function () {
        drRefreshBoard();
        //$("#refresher").click(drRefreshBoard);
    });
</script>
<!--button id="refresher">Get Current Board!</button-->