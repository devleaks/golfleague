<?php

use common\models\Competition;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$buttons = '<div class="btn-group">
	<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">'.Yii::t('golf', 'New').' <span class="caret"></span></button>
	<ul class="dropdown-menu" role="menu">';
foreach(Competition::getConstants('TYPE_') as $competition) {
	$buttons .= '<li>'. Html::a(Yii::t('golf', $competition), ['create', 'type' => $competition]) .'</li>';	
}

$buttons .= '</ul></div>';

$this->title = Yii::t('golf', ucfirst(strtolower(isset($type) ? $type : 'Competitions')));
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="season-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'before'=>$buttons,                                                                                                                                                          	
            'showFooter'=>false
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
                'attribute'=>'name',
				'noWrap' => true,
			],
			[
                'label' => Yii::t('golf', 'Type'),
                'attribute'=>'competition_type',
				'hAlign' => GridView::ALIGN_CENTER,
				'noWrap' => true,
				'visible' => !isset($type),
				'filter' => Competition::getLocalizedConstants('TYPE_'),
			],
            'description',
			[
                'attribute'=>'parent_id',
                'label' => Yii::t('golf', 'Part Of'),
				'hAlign' => GridView::ALIGN_CENTER,
                'value' => function ($model, $key, $index, $widget) {
                    return $model->parent ? $model->parent->name : '';
                },
			],
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
                'label' => Yii::t('golf', 'Status'),
                'value' => function ($model, $key, $index, $widget) {
                    return Yii::t('golf', $model->status);
                },
            ],
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}',
				'noWrap' => true,
			],
        ],
    ]); ?>

</div>
