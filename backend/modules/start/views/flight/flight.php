<?php

use common\models\Rule;

use kartik\grid\GridView;

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<ul id="flight-<?= $flight->id ?>" class="flight panel panel-default">

    <?php
	if ($flight->getCompetition()->isMatchCompetition())
		foreach($flight->getMatches()->each() as $match) {
			$teesColor = 'black';
			echo '<li id="match-'.$match->id.'" class="golfer"  data-handicap="0">'.$match->getLabel(' vs. ').'</li>';
		}
	else if ($flight->getCompetition()->isTeamCompetition())
		foreach($flight->getTeams()->each() as $team) {
			$teesColor = 'black';
			echo '<li id="team-'.$team->id.'" class="golfer"  data-handicap="'.$team->handicap.'"  data-teamsize="'.$team->getRegistrations()->count().'">'.$team->getLabel().' ('.
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
