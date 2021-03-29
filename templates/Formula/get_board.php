<?php
?>
<button onclick="foReloadBoard()">Refresh</button>  <!--TODO: <--remove the temporary button-->
<div id="board" style="position: relative; width: 200%">    <!--//TODO: temporary width only-->
    <?= $this->Html->image('/img/formula/' . $formulaGame->fo_game->fo_track->game_plan,
            ['alt' => 'Formula track map']) ?>
</div>
<div class="carStats" style="position: fixed; right: 0; bottom: 20px;">
    <table id="carStatsTable" style="background-color: white; opacity: .7">
    </table>
</div>
<script>
    var userCarsColors = ["DarkRed", "Red", "DarkGreen", "LightGreen", "DarkBlue", "Blue", "DarkYellow", "Yellow"];
    
    var foInsertCarsOnBoard = function(cars) {
        let carsElement = $(document.createElementNS('http://www.w3.org/2000/svg', "svg"))
                    .addClass("formula_cars")
                    .css("position", "absolute")
                    .offset( { top: 0, left: 0 } )
                    .width("100%")
                    .height("100%");
        $("#board").append(carsElement);
        let radius = .8;
        for (let carIndex in cars) {
            let carElement = $(document.createElementNS("http://www.w3.org/2000/svg", "circle"))
                    .attr("cx", cars[carIndex]["fo_position"]["pos_x"] / 1000 + "%")
                    .attr("cy", cars[carIndex]["fo_position"]["pos_y"] / 1000 + "%")
                    .attr("r", radius + "%")
                    .attr("fill", userCarsColors[carIndex])
                    .attr("style", "opacity: 65%");
            carsElement.append(carElement);
        }
    };
    
    var foInsertCarInfo = function(cars, users) {
        let carStatsTable = $("#carStatsTable");
        for (let car of cars) {
            let carStatRow = $(document.createElement("tr"));
            let user = users.find((_user) => _user["id"] === car["user_id"]);
            carStatRow.append($(document.createElement("td")).html(user["name"]));
            for (let damage of car["fo_damages"]) {
                carStatRow.append($(document.createElement("td"))
                        .addClass("damage" + damage["id"])
                        .html(damage["wear_points"]));
            }
            carStatsTable.append(carStatRow);
        }
    };
    
    var foReloadBoard = function() {
        let url = '<?= \Cake\Routing\Router::url(
                ['action' => 'getBoardUpdateJson', $formulaGame->id]) ?>';
        $.getJSON(url, function(data) {
            //alert(Object.keys(data));
            //alert(JSON.stringify(data));
            //alert(JSON.stringify(data["users"]));
            foInsertCarsOnBoard(data["fo_cars"]);
            foInsertCarInfo(data["fo_cars"], data["users"]);
        });
    };
    
    $(document).ready(function() {
        foReloadBoard();
    });
</script>