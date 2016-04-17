<?php

use common\models\Presentation;

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\icons\Icon;
use insolita\iconpicker\Iconpicker;

/**
 * @var yii\web\View $this
 * @var common\models\Presentation $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Presentations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="presentation-view">

    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'name',
            'display_name',
            'description',
            [
				'attribute' => 'icon',
				'value' => Icon::show(str_replace('fa-', '', $model->icon)),
				'format' => 'raw',
            	'type'=> DetailView::INPUT_WIDGET,
				'widgetOptions' => [
					'class' => Iconpicker::className(),
					'rows' => 6,
					'columns' => 8,
					'iconset'=> 'fontawesome',
				],
			],
			[
                'attribute' => 'fgcolor', 
                'format' => 'raw', 
                'value' => "<span class='badge' style='background-color: {$model->fgcolor}'> </span>  <code>" . $model->fgcolor . '</code>',
                'type'=> DetailView::INPUT_COLOR,
                'valueColOptions' => ['style'=>'width:30%'], 
            ],
			[
                'attribute' => 'bgcolor', 
                'format' => 'raw', 
                'value' => "<span class='badge' style='background-color: {$model->bgcolor}'> </span>  <code>" . $model->bgcolor . '</code>',
                'type'=> DetailView::INPUT_COLOR,
                'valueColOptions' => ['style'=>'width:30%'], 
            ],
            'font',
	        [
	            'attribute'=>'status',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Presentation::getLocalizedConstants('STATUS_'),
	        ],
            [
                'attribute'=>'created_at',
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                'displayOnly'=>true,
            ],
            [
                'attribute'=>'updated_at',
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                'displayOnly'=>true,
            ],
            [
                'attribute'=>'created_by',
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                'displayOnly'=>true,
            ],
            [
                'attribute'=>'updated_by',
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                'displayOnly'=>true,
            ],
        ],
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->id],
            'data'=>[
                'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
                'method'=>'post',
            ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
