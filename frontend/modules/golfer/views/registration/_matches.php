<?php

use common\models\Golfer;

use kartik\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$me = Golfer::me();
?>
<div class="match-index">

   <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => [
	        'heading' => '<h4>'.Yii::t('igolf', 'Simple Matches').'</h4>',
			'before' => Yii::t('igolf', 'Single event matches, not part of another competition.'),
			'beforeOptions' => ['class' => 'alert-info'],
		],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'label' => Yii::t('igolf', 'Competition'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->name;
                },
            ],
            [
                'label' => Yii::t('igolf', 'Date'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->start_date;
                },
            ],
            [
                'label' => Yii::t('igolf', 'Registration start date'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->registration_begin;
                },
            ],
            [
                'label' => Yii::t('igolf', 'Registration end date'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->registration_end;
                },
            ],
            [
                'label' => Yii::t('igolf', 'Handicap'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->handicap_min . '-' . $model->handicap_max;
                },
            ],
            [
                'label' => Yii::t('igolf', 'Age'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->age_min . '-' . $model->age_max;
                },
            ],
            'name',
            [
                'label' => Yii::t('igolf', 'Course'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->course ? $model->course->name : null;
                },
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
				'controller' => 'competition',
				'noWrap' => true,
				'template' => '{view} {register}',
	            'buttons' => [
	                'register' => function ($url, $model) use ($me) {
						if(!$me) return '';
						if ($model->registered($me)) {
							$url = Url::to(['registration/deregister', 'id' => $model->id]);
		                    $a = Html::a('<i class="glyphicon glyphicon-minus"></i>', $url, [
		                        'title' => Yii::t('igolf', 'Deregister'),
		                    ]);
						} else {
							$url = Url::to(['registration/register', 'id' => $model->id]);
		                    $a = Html::a('<i class="glyphicon glyphicon-plus"></i>', $url, [
		                        'title' => Yii::t('igolf', 'Register'),
		                    ]);
						}
						return $a;
	                },
				],
            ],

        ],
    ]); ?>

</div>
