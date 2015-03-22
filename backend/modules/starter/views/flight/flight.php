<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<ul id="flight-<?= $flight->id ?>" class="flight">

    <?php
	foreach($flight->getRegistrations()->each() as $registration) {
		$golfer = $registration->getGolfer()->one();
		$teesColor = isset($registration->getTees()->one()->color) ? $registration->getTees()->one()->color : 'black';
		echo '<li id="registration-'.$registration->id.'" class="golfer"  data-handicap="'.$golfer->handicap.'">'.$golfer->name.' ('.
			'<span class="glyphicon glyphicon-filter" style="color: '.$teesColor.';"></span> '.$golfer->handicap.')</li>';
	}
	?>
	
</ul>
