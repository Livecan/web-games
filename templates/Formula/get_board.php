<?php
    $this->Html->css('formula-game/formula-game', ['block' => true]);
?>
<button onclick="foReloadBoard()">Refresh</button>  <!--TODO: <--remove the temporary button and replace with reloading-->
<div id="board">    <!--//TODO: temporary width only-->
    <?= $this->Html->image('/img/formula/' . $formulaGame->fo_game->fo_track->game_plan,
            ['alt' => 'Formula track map']) ?>
    <svg id="formula_board" />
</div>
<div id="car_stats" onmouseenter="elmtVisibilityToggle(this, false)" 
     onmouseleave="elmtVisibilityToggle(this, true)">
    <table id="car_stats_table" class="damage_table">
    </table>
</div>
<script>
    var foUserCarsColors = ["DarkRed", "Red", "DarkGreen", "LightGreen", "DarkBlue", "Blue", "DarkYellow", "Yellow"];
    var foDamageTypeClass = ["tires", "gearbox", "brakes", "engine", "chassis", "shocks"];
    var foHandlerMoveOptionDamageDisplay = function(event) {
        $(".move_option_damage").css("visibility", "hidden");
        $("#damage_table_" + event.data.positionId).css("visibility", "visible");
    };
    var foHandlerMoveOptionDamageHide = function(event) {
        $("#damage_table_" + event.data.positionId)
                .css("visibility", "hidden");
    };
    var foHandlerChooseMoveOption = function(event) {
        $.post('<?= \Cake\Routing\Router::url(['controller' => 'Formula', 'action' => 'chooseMoveOption', $formulaGame->id]) ?>',
                { _csrfToken: csrfToken, game_id: gameId, move_option_id: event.data.availableMoveId },
                foReloadBoard,
                "json");
    };
    var foHandlerChooseGear = function(event) {
        $.post('<?= \Cake\Routing\Router::url(['controller' => 'Formula', 'action' => 'chooseGear', $formulaGame->id]) ?>',
            { _csrfToken: csrfToken, game_id: gameId, gear: event.data.gear },
            foReloadBoard,
            "json");
    };
    var gameId = <?= $formulaGame->id ?>;
    var modified;
    
    var elmtVisibilityToggle = function(element, visibility) {
        if (visibility) {
            $(element).css("opacity", .9)
        } else {
            $(element).css("opacity", 0)
        }
    }
    var foInsertCarsOnBoard = function(cars) {
        let carsElement = $("#formula_board");
        $("#formula_board .car").remove();
        let radius = .8;
        for (let carIndex in cars) {
            let carElement = $(document.createElementNS("http://www.w3.org/2000/svg", "circle"))
                    .addClass("car")
                    .attr("cx", cars[carIndex]["fo_position"]["pos_x"] / 1000 + "%")
                    .attr("cy", cars[carIndex]["fo_position"]["pos_y"] / 1000 + "%")
                    .attr("r", radius + "%")
                    .attr("fill", foUserCarsColors[carIndex])
                    .css("opacity", "65%");
            carsElement.append(carElement);
        }
    };
    var foGetDamageTdElements = function(damages) {
        let damageTdElements = [];
        for (let damage of damages) {
            damageTdElements.push(
                $(document.createElement("td"))
                    .addClass("damage")
                    .addClass(foDamageTypeClass[damage["fo_e_damage_type_id"] - 1])
                    .html(damage["wear_points"])
                );
        }
        return damageTdElements;
    };
    var foInsertCarInfo = function(cars, users) {
        let carStatsTable = $("#car_stats_table").empty();
        for (let carIndex in cars) {
            let car = cars[carIndex];
            let carStatRow = $(document.createElement("tr"));
            let user = users.find((_user) => _user["id"] === car["user_id"]);
            carStatRow.append(
                    $(document.createElement("td"))
                            .css("background-color", foUserCarsColors[carIndex])
                            .html(user["name"]));
            carStatRow.append(foGetDamageTdElements(car["fo_damages"]));
            carStatsTable.append(carStatRow);
        }
    };
    var foInsertAvailableOptions = function(availableMoves) {
        let boardSvgElement = $("#formula_board");
        let availableMovesByPosition = _.groupBy(availableMoves,
            function (availableMove) {
                return availableMove["fo_position_id"];
        });
        let radius = .8;
        for (let positionId of Object.keys(availableMovesByPosition)) {
            let availableMoves = availableMovesByPosition[positionId];
            let moveElementId = "move_position_" + positionId;
            let circle = $(document.createElementNS("http://www.w3.org/2000/svg", "circle"))
                    .attr("id", moveElementId)
                    .addClass("move_option")
                    .attr("cx", availableMoves[0]["fo_position"]["pos_x"] / 1000 + "%")
                    .attr("cy", availableMoves[0]["fo_position"]["pos_y"] / 1000 + "%")
                    .attr("r", radius + "%")
                    .attr("fill", "purple")
                    .css("opacity", "65%");
            boardSvgElement.append(circle);
            boardSvgElement.on("click", "#" + moveElementId,
                    { positionId: positionId },
                    foHandlerMoveOptionDamageDisplay);
            
            let damageOptionsTableElement = $(document.createElement("table"))
                    .attr("id", "damage_table_" + positionId)
                    .addClass("move_option_damage")
                    .addClass("damage_table")
                    .append($(document.createElement("button"))
                            .attr("id", "button_" + positionId)
                            .html("Close options"));
            $("#board").on("click", "#button_" + positionId,
                    { positionId: positionId },
                    foHandlerMoveOptionDamageHide);
            $("#board").append(damageOptionsTableElement);
            for (let availableMove of availableMoves) {
                let moveOptionId = "move_option_" + availableMove["id"];
                let damageOptionRowElement = $(document.createElement("tr"))
                        .attr("id", moveOptionId)
                        .addClass("damageOption");
                $("#board").on("click", "#" + moveOptionId,
                        { availableMoveId: availableMove["id"] },
                        foHandlerChooseMoveOption);
                damageOptionRowElement.append(
                        foGetDamageTdElements(availableMove["fo_damages"]));
                damageOptionsTableElement.append(damageOptionRowElement);
            }
        }
    };
    var foInsertGearChoice = function(actions) {
        let gearsTable = $(document.createElement("table"))
                .attr("id", "gears")
                .css("position", "fixed")
                .css("left", "30px")
                .css("bottom", "20px")
                .css("background-color", "white")
                .css("width", "auto");
        $("#board").append(gearsTable);
        gearsTable.append(
            _.map(actions["available_gears"], function(gear) {
                let gearId = "gear_" + gear;
                $("#board").on("click", "#" + gearId, { gear: gear }, foHandlerChooseGear);
                return $(document.createElement("tr"))
                        .attr("id", gearId)
                        .html(gear);
            })
        );
        
    };
    var foRemoveMoveOptions = function() {
        $("#formula_board .move_option").remove();
        $("#board .damage_table").remove();
        $("#gears").remove();
    };
    var foRemoveEventHandlers = function() {
        $("#board").off();
        $("#formula_board").off();
    }
    var foReloadBoard = function() {
        let url = '<?= \Cake\Routing\Router::url(
                ['action' => 'getBoardUpdateJson', $formulaGame->id]) ?>';
        $.getJSON(url, {modified: modified}, function(data) {
            if (data["has_updated"]) {
                modified = data["modified"];
                foRemoveMoveOptions();
                foRemoveEventHandlers();
                foInsertCarsOnBoard(data["fo_cars"]);
                foInsertCarInfo(data["fo_cars"], data["users"]);
                //TODO: display debris as well
                if ("actions" in data) {
                    switch (data["actions"]["type"]) {
                        case ("choose_move"):
                            foInsertAvailableOptions(data["actions"]["available_moves"]);
                            break;
                        case ("choose_gear"):
                            foInsertGearChoice(data["actions"]);
                            break;
                    }
                }
            }
        });
    };
    $(document).ready(function() {
        foReloadBoard();
        setInterval(foReloadBoard, 1000);
    });
</script>