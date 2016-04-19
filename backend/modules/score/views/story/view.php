<?php

use common\models\Animation;
use common\models\Presentation;
use common\models\Story;

use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use vova07\imperavi\Widget as Redactor;

/**
 * @var yii\web\View $this
 * @var common\models\Story $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Stories'), 'url' => ['index']];
if($model->story_type == Story::TYPE_PAGE && $model->parent_id) {
	$this->params['breadcrumbs'][] = ['label' => $model->parent->title, 'url' => ['view', 'id' => $model->parent_id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="story-view">

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
            'title',
            'header',
            [
                'attribute'=>'body',
				'format' => 'raw',
				'type' => DetailView::INPUT_WIDGET,
				'widgetOptions' => [
					'class' => Redactor::className(),
					'settings' => [
					    'lang' => 'ru',
					    'minHeight' => 200,
					    'plugins' => [
					        'clips',
					        'fullscreen'
					    ]
					]
				]
				
            ],
			'position',
            [
                'attribute'=>'presentation_id',
                'value'=> $model->presentation ? $model->presentation->display_name : '',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => ['' => ''] + Presentation::getList(),
            ],
            [
                'attribute'=>'animation_id',
                'value'=> $model->animation ? $model->animation->display_name : '',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => ['' => ''] + Animation::getList(),
            ],
            'animation_parameters:ntext',
            'animation_data:ntext',
	        [
	            'attribute'=>'status',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Story::getLocalizedConstants('STATUS_'),
				'value' => $model->makeLabel('status'),
				'format' => 'raw'
	        ],
            [
                'attribute'=>'created_at',
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                'displayOnly'=>true,
            ],
            [
                'attribute'=>'updated_at',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y'],
                'displayOnly'=>true,
            ],
            [
                'attribute'=>'updated_by',
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                'displayOnly'=>true,
            ],
            [
                'attribute'=>'created_by',
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


	<?php
		if($model->story_type == Story::TYPE_STORY) {
			$dataProvider = new ActiveDataProvider([
				'query' => $model->getStories()->orderBy('position'),
			]);

	        echo $this->render('_list', [
	            'dataProvider' => $dataProvider,
				'list_of_values' => $model,
	        ]);
		}
	?>

</div>
