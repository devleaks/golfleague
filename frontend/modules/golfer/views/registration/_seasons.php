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
$this->title = Yii::t('igolf', 'Seasons');
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
                'label' => Yii::t('igolf', 'Competition'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->name;
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
                'label' => Yii::t('igolf', 'Number of Tournaments'),
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
