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
    
    var drTokenElements = $.map([
        "6.png",
        "17.png",
        "9.png",
        "19.png",
    ], function(png, index) { return $(document.createElement("img"))
                .addClass("token")
                .addClass("T" + (index + 1))
                .attr("src", "/img/drowning-game/crystals/" + png)
                .attr("alt", png);
            });
    
    var drGetTokenElements = function(tokens) {
        let tokenElements = [];
        for (let token of tokens) {
            let tokenElement = drTokenElements[token["type"] - 1].clone();
            if (token["value"] !== undefined) {
                tokenElement.text(token["value"]);
            }
            tokenElements.push(tokenElement);
        }
        return tokenElements;
    };
    
    var drFillBoard = function(depths, users) {
        for (let depthNo in depths) {
            let jsonTokens = depths[depthNo].tokens;
            let tokensElement = $("#depth" + depthNo + " .tokens");
            tokensElement.empty();
            tokensElement.append(drGetTokenElements(jsonTokens));
            /*for (let token in jsonTokens) {
                let tokenElement = $(document.createElement("div"))
                        .addClass('token')
                        .addClass('T' + jsonTokens[token]["type"]);
                tokensElement.append(tokenElement);
            }*/
            if (tokensElement.children().length === 0) {
                let img = $(document.createElement("img"))
                        .attr("src", "/img/drowning-game/redX2.png")
                        .attr("style", "position: absolute; width: 80%; height: 80%");
                $("#depth" + depthNo + " .tokens").append(img);
            }
            
            $("#depth" + depthNo + " .diver").remove();
            if (depths[depthNo].diver !== undefined) {
                let diverUserId = depths[depthNo].diver["id"];
                let user = users.find(function(_user) { return _user["id"] === diverUserId; });
                let diverElement = $(document.createElement("div"))
                        .addClass("diver")
                        .addClass("D" + user["order_number"]);
                let diverUserIdElement = $(document.createElement("span"))
                        .addClass("user_id")
                        .attr("hidden", "true")
                        .text(diverUserId.toString());
                diverElement.append(diverUserIdElement);
                
                $("#depth" + depthNo).append(diverElement);
            }
        }
    };
    
    var drFillUsers = function(users) {
        let usersElement = $("#users").empty();
        for (let userIndex in users) {
            let user = users[userIndex];
            
            let tokensTakenElement = $(document.createElement("span"))
                            .addClass("tokens_taken");
            if (user["tokens"] !== undefined) {
                let tokensTaken = user["tokens"][2];
                if (tokensTaken !== undefined) {
                    for (let tokensTakenGroup of Object.values(tokensTaken)) {
                        tokensTakenElement.append(drGetTokenElements(tokensTakenGroup));
                    }
                }
            }
            tokensTakenElement.children().attr("style", "position: relative; width: 30px; height: 30px");
            
            let score = 0;
            if (user["tokens"] !== undefined) {
                let tokensClaimed = user["tokens"][3];
                if (tokensClaimed !== undefined) {
                    score = Object.values(tokensClaimed).reduce(function(total, currentValue) {
                        return total + currentValue.reduce(function(total, currentValue) {
                            return currentValue["value"];
                        }, 0);
                    }, 0);
                }
            }
            let userElement = $(document.createElement("div"))
                    .addClass("user")
                    .append($(document.createElement("span"))
                            .addClass("name")
                            .text(user["name"].toString()))
                    .append(tokensTakenElement)
                    .append($(document.createElement("span"))
                            .addClass("score")
                            .text(score.toString()));
            usersElement.append(userElement);
        }
    };
    
    var drFillOutDivers = function(outDivers) {
        let outDiversElement = $("#outDivers");
        for (let outDiverIndex in outDivers) {
            outDiverElement = $(document.createElement("span"))
                    .addClass("id")
                    .text(outDivers[outDiverIndex]["id"].toString());
            outDiversElement.append(outDiverElement);
        }
    };
    
    var drFillNextTurn = function(nextTurn) {
        let nextTurnElement = $("#nextTurn");
        nextTurnElement.empty();
        if (nextTurn["askTaking"]) {
            let askTakingBtn = $(document.createElement("button"))
                    .text("Take")
                    .on("click", function() {
                        $.post('<?= \Cake\Routing\Router::url(['controller' => 'DrowningGames', 'action' => 'processActions', $game->id]) ?>',
                            { _csrfToken: csrfToken, game_id: drGameId, taking: true, turn_id: drTurnId });
            });
            nextTurnElement.append(askTakingBtn);
        }
        
        if (nextTurn["askReturn"]) {
            let askReturnBtn = $(document.createElement("button"))
                    .text("Return")
                    .on("click", function() {
                        $.post('<?= \Cake\Routing\Router::url(['controller' => 'DrowningGames', 'action' => 'processActions', $game->id]) ?>',
                            { _csrfToken: csrfToken, game_id: drGameId, start_returning: true, turn_id: drTurnId });
            });
            nextTurnElement.append(askReturnBtn);
        }
        
        if (nextTurn["askDropping"]) {
            dropSet = nextTurn["askDropping"];
            console.log(dropSet);
            for (let groupNumber in dropSet) {
                let droppingButton = $(document.createElement("button"))
                        .addClass("askDropBtn");
                groupTokens = dropSet[groupNumber];
                for (let groupToken of groupTokens) {
                    let groupTokenElement = $(document.createElement("div"))
                            .addClass("token")
                            .addClass("T" + groupToken["type"]);
                    droppingButton.append(groupTokenElement);
                }
                nextTurnElement.append(droppingButton);
                droppingButton.on("click", function() {
                    $.post('<?= \Cake\Routing\Router::url(['controller' => 'DrowningGames', 'action' => 'processActions', $game->id]) ?>',
                        { _csrfToken: csrfToken, game_id: drGameId, dropping: true, group_number: groupNumber, turn_id: drTurnId });
                });
            }
        }
        
        nextTurnElement.append($(document.createElement("button"))
                .text("Finish turn")
                .on("click", function() {
                    $.post('<?= \Cake\Routing\Router::url(['controller' => 'DrowningGames', 'action' => 'processActions', $game->id]) ?>',
                        { _csrfToken: csrfToken, game_id: drGameId, finish: true, turn_id: drTurnId });
        }));
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
    };
    
    $(document).ready(function () {
        drRefreshBoard();
        //$("#refresher").click(drRefreshBoard);
    });
</script>
<!--button id="refresher">Get Current Board!</button-->