<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CompetitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Competitions');
$this->params['breadcrumbs'][] = $this->title;

$actionCol = ['class' => 'yii\grid\ActionColumn',
    'template' => '{flight}',
    'buttons' => [
        'flight' => function ($url, $model) {
            return Html::a('<span class="glyphicon glyphicon-calendar"></span>', $url, ['title' => Yii::t('igolf', 'Make Flights')]);;
        },
    ],
    'urlCreator' => function ($action, $model, $key, $index) {
        if (in_array($action, array('flight'))) {
            return Url::to(['flight/competition', 'id' => $model->id ]);
        }
    }
];
?>
<div class="competition-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('igolf', 'Create {modelClass}', [
    'modelClass' => 'Competition',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => Yii::t('igolf', 'Part of'),
                'value' => function($model, $key, $index, $widget) {
                    return $model->name . ' ('.Yii::t('igolf', $model->competition_type).')';
                },
            ],
            'description',
            [
                'label' => Yii::t('igolf', 'Part of'),
                'value' => function($model, $key, $index, $widget) {
                    return $model->parent ? $model->parent->name . ' ('.Yii::t('igolf', $model->parent->competition_type).')' : '-';
                },
            ],
            // 'course_id',
            // 'holes',
            // 'rule_id',
            // 'start_date',
            // 'registration_begin',
            'registration_end',
            // 'handicap_min',
            // 'handicap_max',
            // 'age_min',
            // 'age_max',
            // 'gender',
            // 'status',
            // 'created_at',
            // 'updated_at',

            $actionCol,
        ],
    ]); ?>

</div>
