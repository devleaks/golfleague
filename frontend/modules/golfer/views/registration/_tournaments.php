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
$this->title = Yii::t('golfleague', 'Simple Tournaments');
?>
<div class="season-index">

    <h3><?= Html::encode($this->title) ?></h3>

	<?= Alert::widget([
		'body' => 'Single match or multiple matches tournaments, not part of a larger competition.',
		'options' => ['class'=>'alert-info'],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => Yii::t('golfleague', 'Competition'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->name;
                },
            ],
            [
                'label' => Yii::t('golfleague', 'From'),
				'format' => 'date',
                'value' => function ($model, $key, $index, $widget) {
                    return $model->getStartDate();
                },
            ],
            [
                'label' => Yii::t('golfleague', 'To'),
				'format' => 'date',
                'value' => function ($model, $key, $index, $widget) {
                    return $model->getEndDate();
                },
            ],
            [
                'label' => Yii::t('golfleague', 'Registration start date'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->registration_begin;
                },
            ],
            [
                'label' => Yii::t('golfleague', 'Registration end date'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->registration_end;
                },
            ],
            [
                'label' => Yii::t('golfleague', 'Handicap'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->handicap_min . '-' . $model->handicap_max;
                },
            ],
            [
                'label' => Yii::t('golfleague', 'Age'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->age_min . '-' . $model->age_max;
                },
            ],
            'name',
            [
                'label' => Yii::t('golfleague', 'Number of Matches'),
                'value' => function ($model, $key, $index, $widget) {
					return $model->getMatches()->count();
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
