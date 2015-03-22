<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $title String */
/* @var $searchModel common\models\CompetitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="competition-list">

    <h2><?= Html::encode($title) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel'  => $filterModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Competition'),
                'value' => function ($data) {
                    return Yii::t('golfleague', $data->competition_type);
                },
            ],
            'name',
            'description',
            'course_id',
            // 'holes',
            // 'rule_id',
             'start_date',
            // 'registration_begin',
             'registration_end',
            // 'handicap_min',
            // 'handicap_max',
            // 'age_min',
            // 'age_max',
            // 'gender',
             'status',
            // 'created_at',
            // 'updated_at',
            // 'parent_id',

            ['class' => 'common\models\action\CompetitionGolferActionColumn',
			 'template' => '{view} {update} {result}'],
        ],
    ]); ?>

</div>
