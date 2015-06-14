<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use kartik\icons\Icon;

Icon::map($this); 

/* @var $this yii\web\View */
/* @var $title String */
/* @var $searchModel common\models\CompetitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="competition-list">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'panel' => [
	        'heading' => '<h3>'.$title.'</h3>',
		],
        'columns' => [
//            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            [
                'label' => Yii::t('igolf', 'Competition'),
                'value' => function($model, $key, $index, $widget) {
                    return Yii::t('igolf', $model->competition_type);
                },
            ],
            'name',
            'description',
			[
				'attribute' => 'course_id',
				'value' => function ($model, $key, $index, $widget) {
					return $model->course ? $model->course->name : '';
				}
			],
            // 'holes',
            // 'rule_id',
			[
				'attribute' => 'start_date',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->start_date);
				}
			],
			[
				'attribute' => 'registration_end',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->registration_end);
				}
			],
            // 'registration_begin',
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
