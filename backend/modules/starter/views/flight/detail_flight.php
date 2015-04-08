<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<ul id="flight-<?= $flight->id ?>" class="flight-nohandle">
	<div class="flight-info">
Flight No <span class="flight-number">#</span>—Start Time <span class="flight-time">00:00</span>—Total handicap: <span class="flight-handicap">0</span>.
	</div>

    <?php
	foreach($flight->getRegistrations()->each() as $registration) {
		$golfer = $registration->getGolfer()->one();
		$teesColor = isset($registration->getTees()->one()->color) ? $registration->getTees()->one()->color : 'black';
		if($teesColor == 'white') $teesColor = 'lightgrey';
		echo '<li id="registration-'.$registration->id.'" class="golfer"  data-handicap="'.$golfer->handicap.'">'.$golfer->name.' ('.
			'<span class="glyphicon glyphicon-filter" style="color: '.$teesColor.';"></span> '.$golfer->handicap.')</li>';
	}
	?>
	
</ul>
