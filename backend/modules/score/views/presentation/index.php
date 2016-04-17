<?php

use common\models\Presentation;

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\icons\Icon;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\Presentation $searchModel
 */

$this->title = Yii::t('golf', 'Presentations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="presentation-index">

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'name',
            'display_name',
            'description',
	        [
				'width' => '70px',
				'attribute' => 'icon',
				'filter' => false,
	            'value' => function ($model, $key, $index, $widget) {
							if($model->icon)
								return Icon::show(str_replace('fa-', '', $model->icon)/*, ['style' => ['color' => $model->fgcolor, 'background-color' => $model->bgcolor]]*/);
							else
								return "";
	            		},
				'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
			[
			    'attribute'=>'fgcolor',
			    'value'=>function ($model, $key, $index, $widget) {
			        return "<span class='badge' style='background-color: {$model->fgcolor}'> </span>";
			    },
			    'width'=>'8%',
			    'vAlign'=>'middle',
				'hAlign' => GridView::ALIGN_CENTER,
			    'format'=>'raw',
				'filter' => false,
			],
			[
			    'attribute'=>'bgcolor',
			    'value'=>function ($model, $key, $index, $widget) {
			        return "<span class='badge' style='background-color: {$model->bgcolor}'> </span>";
			    },
			    'width'=>'8%',
			    'vAlign'=>'middle',
				'hAlign' => GridView::ALIGN_CENTER,
			    'format'=>'raw',
				'filter' => false,
			],
            'font', 
            [
				'attribute' => 'status',
				'filter' => Presentation::getLocalizedConstants('STATUS_'),
				'value' => function ($model, $key, $index, $widget) {
							return $model->makeLabel('status');
	            		},
				'format' => 'raw',
				'hAlign' => Gridview::ALIGN_CENTER,
			],

            [
                'class' => 'kartik\grid\ActionColumn',
				'noWrap' => true,
                'buttons' => [
                'update' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['score/presentation/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                ]);
							}
                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
