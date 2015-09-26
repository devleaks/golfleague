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
	if ($competition->isMatchCompetition()) {
		foreach($flight->getMatches()->each() as $match) {
			if($match->getOpponents()->count() == 2) {
				$teesColor = 'black';
				echo '<li id="match-'.$match->id.'" class="golfer"  data-handicap="0">'.$match->getLabel(' vs. ').'</li>';
			}
		}
	} else if ($competition->isTeamCompetition())
		foreach($flight->getTeams()->each() as $team) {
			$teesColor = 'black';
			echo '<li id="team-'.$team->id.'" class="golfer"  data-handicap="'.$team->handicap.'">'.$team->getLabel().' ('.
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
