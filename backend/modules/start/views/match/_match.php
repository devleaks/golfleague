<?php

?>
<li class="match" data-match="<?= $match->id ?>">
	<span class="match-handle glyphicon glyphicon-resize-vertical"></span>
    <?php
	foreach($match->getPlayers()->each() as $player) {
		echo '<div data-registration="'.$player->id.'" class="match-party"><span class="party-handle glyphicon glyphicon-move"></span> '.$player->name.'</div>';
	}
	?>
</li>
