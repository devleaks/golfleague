<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<ul id="team-<?= $team->id ?>" class="flight">

    <?php
	foreach($team->getRegistrations()->each() as $registration) {
		$golfer = $registration->golfer;
		$teesColor = isset($registration->tees->color) ? $registration->tees->color : 'black';
		echo '<li id="registration-'.$registration->id.'" class="golfer"  data-handicap="'.$golfer->handicap.'">'.$golfer->name.' ('.
			'<span class="glyphicon glyphicon-filter" style="color: '.$teesColor.';"></span> '.$golfer->handicap.')</li>';
	}
	?>
	
</ul>
