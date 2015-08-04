<?php

?>
<li class="match" data-match="<?= $match->id ?>">
	<span class="match-handle glyphicon glyphicon-resize-vertical"></span>
    <?php
	foreach($match->getRegistrations()->each() as $registration) {
		echo '<div data-registration="'.$registration->id.'" class="match-party"><span class="party-handle glyphicon glyphicon-move"></span> '.$registration->golfer->name.'</div>';
	}
	?>
</li>
