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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'panel' => [
	        'heading' => '<h4>'.$title.'</h4>',
		],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            [
                'label' => Yii::t('igolf', 'Competition'),
                'value' => function($model, $key, $index, $widget) {
                    return Yii::t('igolf', $model->competition_type);
                },
            ],
            'name',
            'description',
            'course.name',
            'holes',
            'rule.name',
			[
				'attribute' => 'start_date',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->start_date);
				}
			],
            // 'registration_begin',
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

            $actionButtons,
        ],
    ]); ?>

</div>
