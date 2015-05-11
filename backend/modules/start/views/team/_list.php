<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="flight-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
        	'name',
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
	    	'handicap',
        ],
    ]); ?>

</div>
