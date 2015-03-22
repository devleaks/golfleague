<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golfleague', 'Competitions');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="season-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Competition'),
                'value' => function ($data) {
                    return $data->name;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Registration start date'),
                'value' => function ($data) {
                    return $data->registration_begin;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Registration end date'),
                'value' => function ($data) {
                    return $data->registration_end;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Handicap'),
                'value' => function ($data) {
                    return $data->handicap_min . '-' . $data->handicap_max;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Age'),
                'value' => function ($data) {
                    return $data->age_min . '-' . $data->age_max;
                },
            ],
            'name',
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Course'),
                'value' => function ($data) {
                    return $data->course->name;
                },
            ],

            ['class' => 'common\models\action\CompetitionGolferActionColumn'], // $actionCol,

        ],
    ]); ?>

</div>
