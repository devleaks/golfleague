<?php

use kartik\detail\DetailView;
use yii\helpers\ArrayHelper;
use common\models\Course;
use common\models\Practice;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Practice */
?>
<div class="practice-view">
	
    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.$this->title.'</h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
            [
                'attribute'=>'course_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'options' => ['id' => 'course-id'],
				'items' => ArrayHelper::map(Course::find()->asArray()->all(), 'id', 'name'),
                'value' => $model->course ? $model->course->name : '' ,
            ],
            [
                'attribute'=>'tees_id',
				'type' => DetailView::INPUT_DEPDROP,
                'value' => $model->tees ? $model->tees->name : '',
				'widgetOptions' => [
					'pluginOptions'=>[
				        'depends'=>['course-id'],
				        'placeholder'=>'Select...',
				        'url'=>Url::to(['/golfer/practice/tees'])
				    ],
					'data'=> $model->tees ? [$model->tees_id => $model->tees->name] : [],
				],
            ],
            [
                'attribute'=>'start_time',
				'format' => 'datetime',
				'type' => DetailView::INPUT_DATETIME,
				'widgetOptions' => [
					'pluginOptions' => [
	                	'format' => 'yyyy-mm-dd H:i:s',
	                	'todayHighlight' => true
	            	]
				],
            ],
			[
				'attribute' => 'start_hole',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => array(1 => '1', 10 => '10'),
			],
			[
				'attribute' => 'holes',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => array(18 => '18', 9 => '9'),
			],
            'handicap',
			[
				'attribute' => 'status',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Practice::getLocalizedConstants('STATUS_')
			],
        ],
    ]) ?>

</div>
