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
$this->title = Yii::t('golfleague', 'Seasons');
?>
<div class="season-index">

    <h3><?= Html::encode($this->title) ?></h3>

	<?= Alert::widget([
		'body' => 'Tournaments here under are multiple matches tournaments.',
		'options' => ['class'=>'alert-info'],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Competition'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->name;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Registration start date'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->registration_begin;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Registration end date'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->registration_end;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Handicap'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->handicap_min . '-' . $model->handicap_max;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Age'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->age_min . '-' . $model->age_max;
                },
            ],
            'name',
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Number of Tournaments'),
                'value' => function ($model, $key, $index, $widget) {
					return $model->getTournaments()->count();
                },
            ],

            [
                'class' => 'kartik\grid\ActionColumn',
				'template' => '{view}',
				'controller' => 'competition',
				'noWrap' => true,
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
		                        'title' => Yii::t('store', 'Deregister'),
		                    ]);
						} else {
							$url = Url::to(['registration/register', 'id' => $model->id]);
		                    $a = Html::a('<i class="glyphicon glyphicon-plus"></i>', $url, [
		                        'title' => Yii::t('store', 'Register'),
		                    ]);
						}
						return $a;
	                },
				],
            ],
        ],

    ]); ?>

</div>
