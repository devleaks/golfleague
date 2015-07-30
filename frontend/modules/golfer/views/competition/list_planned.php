<?php

use yii\helpers\Html;
use kartik\grid\GridView;
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
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            [
                'label' => Yii::t('golf', 'Competition'),
                'value' => function($model, $key, $index, $widget) {
                    return Yii::t('golf', $model->competition_type);
                },
            ],
            'name',
            'description',
            'course_id',
            // 'holes',
            // 'rule_id',
             'start_date',
             'registration_begin',
            // 'registration_end',
            // 'handicap_min',
            // 'handicap_max',
            // 'age_min',
            // 'age_max',
            // 'gender',
             'status',
            // 'created_at',
            // 'updated_at',
            // 'parent_id',

            ['class' => 'kartik\grid\ActionColumn',
			 'template' => '{view} {update} {delete}'],
        ],
    ]); ?>

</div>
