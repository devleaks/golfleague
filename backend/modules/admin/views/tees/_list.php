<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TeesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Tees');
$tees_color = Yii::$app->golfleague->tee_colors;
?>
<div class="tees-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'panel'=>[
	        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i>  '.Html::encode($this->title).' </h3>',
			'before' => Html::a(Yii::t('golf', 'Add Tee Set'), ['tees/add', 'course_id' => $course->id], ['class' => 'btn btn-success']),
	    ],
		'export' => false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
            	'attribute' => 'name',
            	'label' => Yii::t('golf', 'Tees Name'),
                'value'=> function($model, $key, $index, $widget) use ($tees_color) {
					return $model->name . ($model->holes == 9 ? ' - '.Yii::t('golf', $model->front_back) : '');
				},
			],
			'holes',
            [
                'attribute'=>'color',
                'value'=> function($model, $key, $index, $widget) use ($tees_color) {
					return $tees_color[$model->color];
				},
				'format' => 'raw'
            ],
			'gender',
			'category',
            [
                'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}',
                'controller' => 'tees'
            ],
        ],
    ]); ?>

</div>
