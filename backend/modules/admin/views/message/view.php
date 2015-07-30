<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\Message;

/* @var $this yii\web\View */
/* @var $model common\models\Message */

$this->title = $model->subject;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.$this->title.'</h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
            'subject',
			[
                'attribute'=>'body',
				'type' => DetailView::INPUT_TEXTAREA,
			],
			[
                'attribute'=>'message_start',
				'type' => DetailView::INPUT_DATETIME,
			],
			[
                'attribute'=>'message_end',
				'type' => DetailView::INPUT_DATETIME,
			],
			[
                'attribute'=>'message_type',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Message::getLocalizedConstants('TYPE_'),
			],
			[
                'attribute'=>'status',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Message::getLocalizedConstants('STATUS_'),
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
