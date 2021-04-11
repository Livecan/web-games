<?php

?>
<button onclick="foReloadSetupBoard()">Refresh Setup</button>
<h2 id="game-name"></h2>
<div class="row">
    <div id="player-car-column">
        <table id="player-car-table">
            <thead>
                <tr>
                    <th class="th-name"></th>
                    <th class="th-car"></th>
                    <th class="th-tires"></th>
                    <th class="th-gearbox"></th>
                    <th class="th-brakes"></th>
                    <th class="th-engine"></th>
                    <th class="th-chassis"></th>
                    <th class="th-shocks"></th>
                </tr>
            </thead>
        </table>
    </div>
    <div id="setup-column">
        <div id="track-choice-div">
            <label for="track-choice">Track:</label>
            <select name="track-choice" id="track-choice"
                    onchange="foUpdateSetup('fo_track_id', this.value)">
                <option value="1">Monaco</option>
            </select>
        </div>
        <div>
            <label>Players number</label>
            <label for="min-players">Minimum:</label>
            <input type="number" id="min-players" name="min-players" min="1" max="12" 
                   onchange="foUpdateSetup('min_players', this.value)" />
            <label for="max-players">Maximum:</label>
            <input type="number" id="max-players" name="max-players" min="1" max="12"
                   onchange="foUpdateSetup('max_players', this.value)" />
        </div>
        <div>
            <label for="cars-per-player">Cars per player:</label>
            <input type="number" id="cars-per-player" name="cars-per-player" min="1" max="12"
                   onchange="foUpdateSetup('cars_per_player', this.value)" />
        </div>
        <div>
            <label for="wear-points-available">Wear points available:</label>
            <input type="number" id="wear-points-available" name="wear-points-available" min="6"
                   onchange="foUpdateSetup('wear_points', this.value)" />
        </div>
        <div>
            <label for="laps">Laps:</label>
            <input type="number" id="laps" name="laps" min="1"
                   onchange="foUpdateSetup('laps', this.value)" />
        </div>
    </div>
</div>
<script>
    var modified;
    var foUpdateSetup = function(property, value) {
        let url = '<?= \Cake\Routing\Router::url(
                ['action' => 'editSetup', $formulaGame->id]) ?>';
        let data = { _csrfToken: csrfToken };
        data[property] = value;
        $.post(url, data, foReloadSetupBoard, 'json');
    }
    var foInsertTrackImg = function(track) {
        $("#track-choice-div img").remove();
        $("#track-choice-div").append($(document.createElement("img"))
                .attr("alt", track["game_plan"])
                .attr("src", "/img/formula/" + track["game_plan"]));
    }
    var foInsertSetup = function(formulaGame) {
        $("#track-choice").val(formulaGame["fo_game"]["fo_track_id"]);
        foInsertTrackImg(formulaGame["fo_game"]["fo_track"]);
        $("#min-players").val(formulaGame["min_players"]);
        $("#max-players").val(formulaGame["max_players"]);
        $("#cars-per-player").val(formulaGame["fo_game"]["cars_per_player"]);
        $("#wear-points-available").val(formulaGame["fo_game"]["wear_points"]);
        $("#laps").val(formulaGame["fo_game"]["laps"]);
        if (formulaGame["editable"] && $("#start-button").length === 0) {
            $("#setup-column").append($(document.createElement("button"))
                    .attr("id", "start-button")
                    .attr("disabled", true)
                    .text("Start"));
        }
    };
    var foInsertPlayerCars = function(users) {
        let playerCarTable = $("#player-car-table");
        $("#player-car-table .player-row").remove();
        $("#player-car-table .car-row").remove();
        for (let user of users) {
            let playerNameElmt = $(document.createElement("tr"))
                    .addClass("player-row")
                    .append($(document.createElement("td"))
                            .attr("colspan", 8)
                            .text(user['name']));
            playerCarTable.append(playerNameElmt);
            for (let car of user["fo_cars"]) {
                let carElmt = $(document.createElement("tr"))
                        .addClass("car-row")
                        .append($(document.createElement("td")))
                        .append($(document.createElement("td")));
                for (let damage of _.sortBy(car["fo_damages"], 'fo_e_damage_type_id')) {
                    carElmt.append($(document.createElement("td"))
                            .text(damage["wear_points"]));
                }
                playerCarTable.append(carElmt);
            }
        }
    };
    var foReloadSetupBoard = function() {
        let url = '<?= \Cake\Routing\Router::url(
                ['action' => 'getSetupUpdateJson', $formulaGame->id]) ?>';
        $.getJSON(url, {modified: modified}, function(data) {
            $("#game-name").text(data['name']);
            foInsertPlayerCars(data["users"]);
            foInsertSetup(data);
        });
    };
    $(document).ready(function() {
        foReloadSetupBoard();
    });
</script>