<?php

use common\models\Story;

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\Story $searchModel
 */
?>
<div class="story-index">

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'kartik\grid\SerialColumn'],

        	'position',
            'title',
//            'header',
//            'body:ntext',
            'presentation.display_name', 
            'animation.display_name', 
//            'animation_parameters:ntext', 
//            'animation_data:ntext', 
			[
				'attribute' => 'status',
				'filter' => Story::getLocalizedConstants('STATUS_'),
				'value' => function ($model, $key, $index, $widget) {
							return $model->makeLabel('status');
			    		},
				'format' => 'raw',
				'hAlign' => Gridview::ALIGN_CENTER,
			],
            'created_at', 
            'updated_at', 

            [
                'class' => 'kartik\grid\ActionColumn',
				'noWrap' => true,
                'buttons' => [
                'update' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['score/story/view','id' => $model->id,'edit'=>'t']), [
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
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('yii', 'Story Pages').' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['add', 'id' => $list_of_values->id], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
