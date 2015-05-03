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
					foreach($model->getRegistrations()->each() as $registration) {
						$s .= '<li>'.$registration->golfer->name.'</li>';
					}
					return $s . '</ol>';
				},
				'format' => 'raw',
			],
			'start_time:datetime',
			[
				'attribute' => 'start_hole',
				'value' => function($model, $key, $index, $widget) {
					return $model->start_hole ? $model->start_hole : 1;
				}
			],
			'note',
        ],
    ]); ?>

</div>
