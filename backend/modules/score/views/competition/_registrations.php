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
	        'heading' => '<h4>'.Yii::t('golf', 'Registrations').'</h4>',
			'footer' => false,
	    ],
		'pjax' => true,
		'pjaxSettings' => [
	        'neverTimeout' => true,
        ],
		'export' => false,
        'columns' => [
            [
            	'attribute' => 'competition_name',
                'label' => Yii::t('golf', 'Competition'),
                'value' => function($model, $key, $index, $widget) {
                    return  $model->competition->name;
                },
				'visible' => $competition === null,
            ],
            [
            	'attribute' => 'competition_type',
                'label' => Yii::t('golf', 'Competition Type'),
                'value' => function($model, $key, $index, $widget) {
                    return  Yii::t('golf', $model->competition->competition_type);
                },
				'filter' => Competition::getLocalizedConstants('TYPE_'),
				'visible' => $competition === null,
            ],
            [
            	'attribute' => 'golfer_name',
                'label' => Yii::t('golf', 'Golfer'),
                'value' => function($model, $key, $index, $widget) {
                    return  $model->golfer->name;
                },
			],
            [
            	'attribute' => 'tee_time',
                'label' => Yii::t('golf', 'Tee time'),
	            'value' => function($model, $key, $index, $widget) {
	                return  $model->flight ? $model->flight->start_time : '';
	            },
				'format' => 'time',
				'noWrap' => true,
			],
            [
                'attribute' => 'status',
                'value' => function($model, $key, $index, $widget) {
                	return Yii::t('golf', $model->status);
                },
				'filter' => Registration::getLocalizedPostCompetitionStatuses(),
            ],
        ],
    ]); ?>

</div>