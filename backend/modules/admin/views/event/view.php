<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\Event;

/* @var $this yii\web\View */
/* @var $model common\models\Event */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.$model->name.'</h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
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
