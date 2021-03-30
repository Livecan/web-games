<?php
?>
<button onclick="foReloadBoard()">Refresh</button>  <!--TODO: <--remove the temporary button-->
<div id="board" style="position: relative; width: 200%">    <!--//TODO: temporary width only-->
    <?= $this->Html->image('/img/formula/' . $formulaGame->fo_game->fo_track->game_plan,
            ['alt' => 'Formula track map']) ?>
</div>
<div class="carStats" onmouseenter="elmtVisibilityToggle(this, false)" 
     onmouseleave="elmtVisibilityToggle(this, true)"
     style="position: fixed; right: 0; bottom: 20px; opacity: .9;
     transition-property: opacity; transition-duration: .5s;">
    <table id="carStatsTable" style="background-color: white; color: black; font-weight: bold">
    </table>
</div>
<script>
    var userCarsColors = ["DarkRed", "Red", "DarkGreen", "LightGreen", "DarkBlue", "Blue", "DarkYellow", "Yellow"];
    var damageColors = ["SlateBlue", "SeaGreen", "Turqoise", "Tomato", "Orange", "Orchid"];
    
    var elmtVisibilityToggle = function(element, visibility) {
        if (visibility) {
            $(element).css("opacity", .9)
        } else {
            $(element).css("opacity", 0)
        }
        //alert(visibility);
    }  //TODO do mouse events to these two functions.
    
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
                    .css("opacity", "65%");
            carsElement.append(carElement);
        }
    };
    
    var foInsertCarInfo = function(cars, users) {
        let carStatsTable = $("#carStatsTable").empty();
        for (let carIndex in cars) {
            let car = cars[carIndex];
            let carStatRow = $(document.createElement("tr"));
            let user = users.find((_user) => _user["id"] === car["user_id"]);
            carStatRow.append(
                    $(document.createElement("td"))
                            .css("background-color", userCarsColors[carIndex])
                            .html(user["name"]));
            for (let damageIndex in car["fo_damages"]) {
                let damage = car["fo_damages"][damageIndex];
                carStatRow.append($(document.createElement("td"))
                        .addClass("damage" + damage["id"])
                        .css("background-color", damageColors[damageIndex])
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