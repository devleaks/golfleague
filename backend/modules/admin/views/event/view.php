<?php

use common\models\Event;
use common\models\Facility;
use common\models\League;

use kartik\detail\DetailView;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Event */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-cutlery"></i>  '.Html::encode($this->title).' </h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
            [
                'attribute'=>'league_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => ArrayHelper::map(League::find()->asArray()->all(), 'id', 'name'),
                'value'=> $model->league ? $model->league->name : '',
            ],
            [
                'attribute'=>'facility_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => ArrayHelper::map(Facility::find()->asArray()->all(), 'id', 'name'),
                'value'=> $model->facility ? $model->facility->name : '',
            ],
            'name',
            'description',
			[
                'attribute'=>'event_start',
				'type' => DetailView::INPUT_DATETIME,
			],
			[
                'attribute'=>'event_end',
				'type' => DetailView::INPUT_DATETIME,
			],
            [
                'attribute'=>'event_type',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Event::getConstants('TYPE_'),
            ],
			[
                'attribute'=>'status',
				'displayOnly' => true,
			],
			[
                'attribute'=>'created_at',
				'format' => 'datetime',
				'displayOnly' => true,
				'noWrap' => true,
				'value' => new DateTime($model->created_at),
			],
			[
                'attribute'=>'updated_at',
				'format' => 'datetime',
				'displayOnly' => true,
				'noWrap' => true,
				'value' => new DateTime($model->updated_at),
			],
        ],
    ]) ?>

	<?=	$this->render('../media/_add', [
		'model' => $model,
	])?>

</div>
