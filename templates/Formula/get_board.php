<?php
    $this->Html->css('formula-game/formula-game', ['block' => true]);
    $this->Html->script('/js/jQueryRotate.js', ['block' => true]);
?>
<button onclick="foReloadBoard()">Refresh</button>  <!--TODO: <--remove the temporary button and replace with reloading-->
<button class="zoom_button" onclick="foHandlerZoomBoard(true)">+</button>
<button class="zoom_button" onclick="foHandlerZoomBoard(false)">-</button>
<div class="row">
    <div id="board_parent" style="overflow: auto">
        <div id="board">
            <?= $this->Html->image('/img/formula/' . $formulaGame->fo_game->fo_track->game_plan,
                    ['alt' => 'Formula track map']) ?>
            <svg id="formula_board" />
        </div>
    </div>
</div>
<div class="row">
    <div id="logger">
    </div>
</div>
<div id="car_stats" onmouseenter="elmtVisibilityToggle(this, false)" 
     onmouseleave="elmtVisibilityToggle(this, true)">
    <table id="car_stats_table" class="damage_table">
    </table>
</div>
<script>
    var foUserCarsColors = ["DarkRed", "Red", "DarkGreen", "LightGreen", "DarkBlue", "Blue", "DarkYellow", "Yellow"];
    var foDamageTypeClass = ["tires", "gearbox", "brakes", "engine", "chassis", "shocks"];
    var foCarImages = ["tdrc01_car01_b.png",
        "tdrc01_car01_e.png",
        "tdrc01_car01_f.png",
        "tdrc01_car03_a.png",
        "tdrc01_car03_c.png",
        "tdrc01_car03_d.png",
        "tdrc01_car04_a.png",
        "tdrc01_car04_d.png",
        "tdrc01_car04_f.png",
        "tdrc01_car07_b.png",
        "tdrc01_car07_d.png",
        "tdrc01_car07_f.png"
    ];
    var foBoardZooms = ["100%", "150%", "200%", "300%", "400%"];
    var foBoardCurrentZoomIndex = 0;
    var foHandlerZoomBoard = function(zoomIn) {
        if (zoomIn) {
            foBoardCurrentZoomIndex = Math.min(foBoardCurrentZoomIndex + 1, foBoardZooms.length - 1);
        } else {
            foBoardCurrentZoomIndex = Math.max(foBoardCurrentZoomIndex - 1, 0);
        }
        $("#board").css("width", foBoardZooms[foBoardCurrentZoomIndex]);
    }
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
            $(element).css("opacity", .9);
        } else {
            $(element).css("opacity", 0);
        }
    };
    var foInsertCarsOnBoard = function(cars) {
        let carsElement = $("#board");
        $("#board .car_img").remove();
        let carWidth = .8;
        let carLength = 2.5 * carWidth;
        for (let carIndex in cars) {
            let carElement;
            carElement = $(document.createElement("img"))
                    .addClass("car_img")
                    .attr("src", "/img/formula/cars/" + foCarImages[carIndex])
                    .css("left", cars[carIndex]["fo_position"]["pos_x"] / 1000 - carWidth / 2 + "%")
                    .css("top", cars[carIndex]["fo_position"]["pos_y"] / 1000 - carLength / 2 + "%")
                    .attr("width", carWidth + "%")
                    .attr("height", carLength + "%")
                    .rotate(cars[carIndex]["fo_position"]["angle"] * 180 / Math.PI - 90);
            $("#board").append(carElement);

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
        _.each(cars, function(car, carIndex) {
            car["carIndex"] = carIndex;
        });
        orderedCars = _.sortBy(cars, function(car) {
            return car["order"] ?? Infinity;
        });
        for (let car of orderedCars) {
            let carStatRow = $(document.createElement("tr"));
            let user = users.find((_user) => _user["id"] === car["user_id"]);
            carStatRow.append(
                $(document.createElement("td")).append(
                    $(document.createElement("img"))
                        .addClass("car_img")
                        .attr("width", "20px")
                        .attr("height", "50px")
                        .attr("src", "/img/formula/cars/" + foCarImages[car["carIndex"]])
                        ));
            carStatRow.append(
                    $(document.createElement("td"))
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
                    .attr("fill", "purple");
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
                .attr("id", "gears");
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
    var foFeedLogger = function(logs, users) {
        alert(JSON.stringify(logs));
        let logger = $("#logger");
        logger.empty();
        for (let log of logs) {
            logger.append(
                $(document.createElement("div"))
                    .html("carId: " + log["fo_car_id"] + ", gear: " + log["gear"] +
                    ", rolled: " + log["roll"] + ", logType: " + log["type"])
            );
        }
    }
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
                foFeedLogger(data["fo_logs"], data["users"]);
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