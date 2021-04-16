<?php
    $this->Html->css('formula-game/formula-setup', ['block' => true]);
?>
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
        <table>
            <tr>
                <td>
                    <label for="track-choice">Track:</label>
                </td>
                <td>
                    <select name="track-choice" id="track-choice"
                            onchange="foUpdateSetup('fo_track_id', this.value)">
                        <option value="1">Monaco</option>
                    </select>
                </td>
            <tr>
            <tr>
                <td id="track-choice-div" colspan="2"/>
            </tr>
            <tr>
                <td colspan="2">
                    <label>Players number</label>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="min-players">Minimum:</label>
                </td>
                <td>
                    <input type="number" id="min-players" name="min-players" min="1" max="12" 
                       onchange="foUpdateSetup('min_players', this.value)" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="max-players">Maximum:</label>
                </td>
                <td>
                    <input type="number" id="max-players" name="max-players" min="1" max="12"
                       onchange="foUpdateSetup('max_players', this.value)" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cars-per-player">Cars per player:</label>
                </td>
                <td>
                    <input type="number" id="cars-per-player" name="cars-per-player" min="1" max="12"
                       onchange="foUpdateSetup('cars_per_player', this.value)" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wear-points-available">Wear points available:</label>
                </td>
                <td>
                    <input type="number" id="wear-points-available" name="wear-points-available" min="6"
                       onchange="foUpdateSetup('wear_points', this.value)" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="laps">Laps:</label>
                </td>
                <td>
                    <input type="number" id="laps" name="laps" min="1"
                       onchange="foUpdateSetup('laps', this.value)" />
                </td>
            </tr>
            <tr>
                <td>
                    <?= $this->Form->postButton(__('Start'),
                            ['action' => 'start', $formulaGame->id],
                            ['id' => 'start-button']) ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<script>
    var modifiedSetup;
    var foUpdateSetup = function(property, value) {
        let url = '<?= \Cake\Routing\Router::url(
                ['action' => 'editSetup', $formulaGame->id]) ?>';
        let data = { _csrfToken: csrfToken };
        data[property] = value;
        $.post(url, data, null, 'json');
    };
    var foUpdateDamage = function(damageId, wearPoints) {
        let url = '<?= \Cake\Routing\Router::url(
                ['action' => 'editDamage', $formulaGame->id]) ?>';
        let data = { _csrfToken: csrfToken, damage_id: damageId, wear_points: wearPoints };
        $.post(url, data, null, 'json');
    };
    var foStartGame = function() {
        let url = '<?= \Cake\Routing\Router::url(
                ['action' => 'start', $formulaGame->id]) ?>';
        let data = { _csrfToken: csrfToken };
        $.post(url, data, null, 'json');
    }
    var foInsertTrackImg = function(track) {
        $("#track-choice-div img").remove();
        $("#track-choice-div").append($(document.createElement("img"))
                .attr("alt", track["game_plan"])
                .attr("src", "/img/formula/" + track["game_plan"]));
    }
    var foInsertSetup = function(formulaGame) {
        if (!formulaGame["editable"]) {
            $("#setup-column *").attr("disabled", true);
        }
        $("#track-choice").val(formulaGame["fo_game"]["fo_track_id"]);
        foInsertTrackImg(formulaGame["fo_game"]["fo_track"]);
        $("#min-players").val(formulaGame["min_players"]);
        $("#max-players").val(formulaGame["max_players"]);
        $("#cars-per-player").val(formulaGame["fo_game"]["cars_per_player"]);
        $("#wear-points-available").val(formulaGame["fo_game"]["wear_points"]);
        $("#laps").val(formulaGame["fo_game"]["laps"]);
        if (formulaGame["editable"]) {
            //TODO: disable start-button if not editable or not ready
        }
    };
    var foInsertPlayerCars = function(users, carsPerPlayer) {
        let playerCarTable = $("#player-car-table");
        $("#player-car-table .player-row").remove();
        $("#player-car-table .car-row").remove();
        for (let user of users) {
            let editable = user["editable"];
            let playerNameElmt = $(document.createElement("tr"))
                    .addClass("player-row")
                    .append($(document.createElement("td"))
                            .attr("colspan", 8)
                            .append(
                                $(document.createElement("span")).text(user['name']))
                            .append(
                                $(document.createElement("button"))
                                        .addClass("ready-button")
                                        .text("READY?"))
                            );
            playerCarTable.append(playerNameElmt);
            for (let car of user["fo_cars"].slice(0, carsPerPlayer)) {
                let carElmt = $(document.createElement("tr"))
                        .addClass("car-row")
                        .append($(document.createElement("td")))
                        .append($(document.createElement("td")));
                for (let damage of _.sortBy(car["fo_damages"], 'fo_e_damage_type_id')) {                    
                    carElmt.append($(document.createElement("td"))
                            .html($(document.createElement("input"))
                                    .attr("id", "damage" + damage["id"])
                                    .attr("type", "number")
                                    .attr("disabled", !editable)
                                    .val(damage["wear_points"])
                                    .change(
                                        function() {
                                            foUpdateDamage(damage["id"], this.value);
                                        })
                                    ));
                }
                playerCarTable.append(carElmt);
            }
        }
    };
    var foReloadSetupBoard = function() {
        let url = '<?= \Cake\Routing\Router::url(
                ['action' => 'getSetupUpdateJson', $formulaGame->id]) ?>';
        $.getJSON(url, { 'modified-setup': modifiedSetup }, function(data) {
            if (data["has_updated"]) {
                if (data["has_started"]) {
                    window.location.href = '<?= \Cake\Routing\Router::url(
                        ['action' => 'getBoard', $formulaGame->id]) ?>';
                }
                modifiedSetup = data["modified"];
                $("#game-name").text(data['name']);
                foInsertPlayerCars(data["users"], data["fo_game"]["cars_per_player"]);
                foInsertSetup(data);
            }
        });
    };
    $(document).ready(function() {
        foReloadSetupBoard();
        setInterval(foReloadSetupBoard, 2000);
    });
</script>