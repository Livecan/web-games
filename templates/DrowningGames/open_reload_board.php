<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DrowningGame $game
 */

$this->Html->css('drowning-game/board', ['block' => true]);
?>
<div id="game_id" hidden="true">$game->id</div>
<div id="oxygen">
</div>
<div id="ocean">
    <?php for ($i = 1; $i <= 20; $i++): ?>
    <div class="depth">
        <div class="tokens">
        </div>
    </div>
    <?php endfor; ?>
</div>
<div id="users">
</div>
<div id="nextTurn">
</div>
<script>
    $(document).ready(function(){
        $("button").click(function() {
            $.getJSON("/drowning-games/update-board-json/1", function(data, status){
               alert("Status: " + status + "\nData: " + JSON.stringify(data));
            });
        });
    });
</script>
<button>Get Current Board!</button>