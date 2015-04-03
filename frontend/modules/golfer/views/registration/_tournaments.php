<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golfleague', 'Simple Tournaments');
?>
<div class="season-index">

    <h2><?= Html::encode($this->title) ?></h2>

	<?= Alert::widget([
		'body' => 'Tournaments here under are single or multiple matches tournaments.',
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
                'label' => Yii::t('golfleague', 'Number of Matches'),
                'value' => function ($model, $key, $index, $widget) {
					return $model->getMatches()->count();
                },
            ],

            [
                'class' => 'kartik\grid\ActionColumn',
				'template' => '{view}',
				'controller' => 'competition',
				'noWrap' => true,
            ],

        ],
    ]); ?>

</div>
