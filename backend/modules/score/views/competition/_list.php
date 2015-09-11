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
	        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($title).' </h3>',
			'type' => Gridview::TYPE_SUCCESS,
			'footer' => false,
		],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'label' => Yii::t('golf', 'Competition'),
                'value' => function($model, $key, $index, $widget) {
                    return Yii::t('golf', $model->competition_type);
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
            'status',
            $actionButtons,
        ],
    ]); ?>

</div>
