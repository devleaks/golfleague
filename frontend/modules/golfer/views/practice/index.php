<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PracticeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Practice Rounds');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="practice-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> '.Html::encode($this->title).' </h3>',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          	
//			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'course.name',
            [
				'attribute' => 'start_time',
                'format' => 'datetime',
				'noWrap' => true,
            ],
            'tees.name',
            'start_hole',
            'holes',
            'handicap',
            'status',
            // 'updated_at',
            'created_at',

			[
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {update} {delete}',
			],
        ],
    ]); ?>

</div>
