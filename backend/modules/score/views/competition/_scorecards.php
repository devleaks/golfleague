<?php

use common\models\Competition;
use common\models\Registration;
use common\models\Scorecard;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="registration-list">

     <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'panel'=>[
	        'heading' => '<h4>'.Yii::t('golf', 'Scorecards').'</h4>',
			'footer' => false,
	    ],
		'pjax' => true,
		'pjaxSettings' => [
	        'neverTimeout' => true,
        ],
		'export' => false,
        'columns' => [
            [
            	'attribute' => 'golfer_name',
                'label' => Yii::t('golf', 'Golfer'),
                'value' => function($model, $key, $index, $widget) {
                    return  $model->player->name;
                },
			],
			'score',
			'score_net',
			'stableford',
			'stableford_net',
            [
            	'attribute' => 'points',
                'value' => function($model, $key, $index, $widget) {
					if($rule = $model->registration->competition->rule)
						return $rule->formatPoints($model->points);
                    return  $model->points;
                },
			],
			'rounds',
            [
                'attribute' => 'status',
            	'label' => Yii::t('golf', 'Scorecard Status'),
                'value' => function($model, $key, $index, $widget) {
                	return Yii::t('golf', $model->status);
                },
				'filter' => Registration::getLocalizedPostCompetitionStatuses(),
            ],
        ],
    ]); ?>

</div>