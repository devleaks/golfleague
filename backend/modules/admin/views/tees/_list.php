<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TeesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Tees');
$tees_color = Yii::$app->params['tees_colors'];
?>
<div class="tees-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel'=>[
	        'heading' => '<h4>'.$this->title.'</h4>',
			'footer' => Html::a(Yii::t('igolf', 'Add Tee Set'), ['tees/add', 'course_id' => $course->id], ['class' => 'btn btn-primary']),
	    ],
		'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
            	'attribute' => 'name',
            	'label' => Yii::t('igolf', 'Tees Name'),
                'value'=> function($model, $key, $index, $widget) use ($tees_color) {
					return $model->name . ($model->holes == 9 ? ' - '.Yii::t('igolf', $model->front_back) : '');
				},
			],
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
                'class' => 'yii\grid\ActionColumn',
				'template' => '{view} {delete}',
                'controller' => 'tees'
            ],
        ],
    ]); ?>

</div>
