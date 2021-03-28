<?php
?>
<button onclick="foReloadBoard()">Refresh</button>  <!--TODO: <--remove the temporary button-->
<div id="board" style="position: relative; width: 300%">    <!--//TODO: temporary width only-->
    <?= $this->Html->image('/img/formula/' . $formulaGame->fo_game->fo_track->game_plan,
            ['alt' => 'Formula track map']) ?>
</div>
<script>
    var foInsertCars = function(cars) {
        let boardElement = $("#board");
        let width = 1, height = 1;
        for (let car of cars) {
            let carElement = $(document.createElement("span"))
                    .addClass("dot")
                    .addClass("car")
                    .addClass("U" + car["user_id"])
                    .attr("style", "position: absolute; left: " +
                        (car["fo_position"]["pos_x"] / 1000 - width/2) +
                        "%; top: " + (car["fo_position"]["pos_y"] / 1000 - height/2) + "%; " +
                        "width: " + width + "%; height: " + height + "%; background-color: black; " +
                        "border-radius: 50%;");
            $("#board").append(carElement);
        }
    };
    
    var foReloadBoard = function() {
        let url = '<?= \Cake\Routing\Router::url(
                ['action' => 'getBoardUpdateJson', $formulaGame->id]) ?>';
        alert(url);
        $.getJSON(url, function(data) {
            alert(JSON.stringify(data));
            foInsertCars(data["fo_cars"]);
        });
    };
    
    $(document).ready(function() {
        foReloadBoard();
    });
</script>