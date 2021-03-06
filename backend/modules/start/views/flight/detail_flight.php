<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<ul id="flight-<?= $flight->id ?>" class="flight-nohandle">
	<div class="flight-info">
Flight No <span class="flight-number"><?= $number ?></span> — Start Time <span class="flight-time"><?= $flight->start_time ?></span>.
	</div>

    <?php
	foreach($flight->getRegistrations()->each() as $registration) {
		$golfer = $registration->golfer;
		$teesColor = isset($registration->tees->color) ? $registration->tees->color : 'black';
		if($teesColor == 'white') $teesColor = 'lightgrey';
		echo '<li id="registration-'.$registration->id.'" class="golfer"  data-handicap="'.$golfer->handicap.'">'.$golfer->name.' ('.
			'<span class="glyphicon glyphicon-filter" style="color: '.$teesColor.';"></span> '.$golfer->handicap.')</li>';
	}
	?>
	
</ul>
