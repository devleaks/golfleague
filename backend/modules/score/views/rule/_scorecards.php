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
		'options' => ['id' => 'registration'],
        'dataProvider' => $dataProvider,
		'panel'=>[
	        'heading' => '<h4>'.Yii::t('igolf', 'Scorecards').'</h4>',
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
                'label' => Yii::t('igolf', 'Golfer'),
                'value' => function($model, $key, $index, $widget) {
                    return  $model->golfer->name;
                },
			],
			'score',
			'score_net',
			'stableford',
			'stableford_net',
			'points',
            [
                'attribute' => 'status',
            	'label' => Yii::t('igolf', 'Scorecard Status'),
                'value' => function($model, $key, $index, $widget) {
                	return Yii::t('igolf', $model->status);
                },
				'filter' => Registration::getLocalizedPostCompetitionStatuses(),
            ],
        ],
    ]); ?>

</div>