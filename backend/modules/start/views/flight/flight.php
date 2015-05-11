<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<ul id="flight-<?= $flight->id ?>" class="flight">

    <?php
	if ($flight->competition->isTeamCompetition())
		foreach($flight->getTeams()->each() as $team) {
			$teesColor = 'black';
			echo '<li id="registration-'.$team->id.'" class="golfer"  data-handicap="'.$team->handicap.'">'.$team->name.' ('.
				'<span class="glyphicon glyphicon-filter" style="color: '.$teesColor.';"></span> '.$team->handicap.')</li>';
		}
	else
		foreach($flight->getRegistrations()->each() as $registration) {
			$player = $registration->golfer;
			$teesColor = isset($registration->tees->color) ? $registration->tees->color : 'black';
			echo '<li id="registration-'.$registration->id.'" class="golfer"  data-handicap="'.$player->handicap.'">'.$player->name.' ('.
				'<span class="glyphicon glyphicon-filter" style="color: '.$teesColor.';"></span> '.$player->handicap.')</li>';
		}
	?>
	
</ul>
