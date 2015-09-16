<?php

use common\models\Competition;

use kartik\grid\GridView;

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="subcompetition-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
	        'heading' => '<h3 class="panel-title">'.$parent->getFullName().' <small>('.Yii::t('golf', $parent->childType()).')</small></h3>',
            'showFooter'=>false
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
	            'attribute'=>'name',
				'noWrap' => true,
				'format' => 'raw',				
                'value' => function ($model, $key, $index, $widget) {
                    return Html::a($model->name, ['view', 'id'=>$model->id]);
                },
			],
			[
                'attribute'=>'competition_type',
				'hAlign' => GridView::ALIGN_CENTER,
				'noWrap' => true,
				'visible' => !isset($type),
				'filter' => Competition::getLocalizedConstants('TYPE_'),
			],
            'description',
			[
                'attribute'=>'registration_begin',
				'format' => 'datetime',
				'hAlign' => GridView::ALIGN_CENTER,
                'value' => function ($model, $key, $index, $widget) {
                    return new DateTime($model->registration_begin);
                },
				'noWrap' => true,
			],
			[
                'attribute'=>'registration_end',
				'format' => 'datetime',
				'hAlign' => GridView::ALIGN_CENTER,
                'value' => function ($model, $key, $index, $widget) {
                    return new DateTime($model->registration_end);
                },
				'noWrap' => true,
			],
            [
            	'attribute'=>'status',
                'label' => Yii::t('golf', 'Status'),
				'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
				'filter' => Competition::getLocalizedConstants('STATUS_'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->makeLabel($model->status);
                },
            ],
        ],
    ]); ?>

</div>
