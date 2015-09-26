<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="flight-index">

	<label class="control-label">Competition start:</label> 
	<strong><?= Yii::$app->formatter->asDateTime($competition->start_date) ?></strong>.

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
        	'position',
			[
				'attribute' => 'golfers',
				'value' => function($model, $key, $index, $widget) {
					$s = '<ol class="flight">';
					$competition = $model->getCompetition();
					if ($competition->isMatchCompetition()) {
						foreach($model->getMatches()->each() as $match) {
							$teesColor = 'black';
							$s .= '<li>'.$match->getLabel(' vs. ').'</li>';
						}
					} else if ($competition->isTeamCompetition())
						foreach($model->getTeams()->each() as $team) {
							$teesColor = 'black';
							$s .= '<li>'.$team->getLabel().' (<span class="glyphicon glyphicon-filter" style="color: '.$teesColor.';"></span> '.$team->handicap.')</li>';
						}
					else
						foreach($model->getRegistrations()->each() as $registration) {
							$player = $registration->golfer;
							$teesColor = isset($registration->tees->color) ? $registration->tees->color : 'black';
							$s .= '<li>'.$player->name.' (<span class="glyphicon glyphicon-filter" style="color: '.$teesColor.';"></span> '.$player->handicap.')</li>';
						}

					return $s . '</ol>';
				},
				'format' => 'raw',
			],
			[/** php datetime bug for timezone */
				'attribute' => 'start_time',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->start_time);
				}
			],
			[
				'attribute' => 'start_hole',
				'value' => function($model, $key, $index, $widget) {
					return $model->start_hole ? $model->start_hole : 1;
				}
			],
			'handicap',
			'note',
        ],
    ]); ?>

</div>
