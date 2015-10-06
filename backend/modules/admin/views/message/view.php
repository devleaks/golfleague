<?php

use common\models\Facility;
use common\models\League;
use common\models\Message;

use vova07\imperavi\Widget as RedactorWidget;

use kartik\detail\DetailView;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

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
	        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-comment"></i> '.Html::encode($this->title).' </h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
            [
                'attribute'=>'league_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => ArrayHelper::map(League::find()->asArray()->all(), 'id', 'name'),
                'value'=>$model->facility->name,
            ],
            [
                'attribute'=>'facility_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => ArrayHelper::map(Facility::find()->asArray()->all(), 'id', 'name'),
                'value'=>$model->facility->name,
            ],
            'subject',
			[
                'attribute'=>'body',
				'type' => DetailView::INPUT_WIDGET,
				'widgetOptions' => [
					'class' => RedactorWidget::className(),
				    'settings' => [
				        'lang' => 'fr',
				        'minHeight' => 300,
				        'plugins' => [
				            'clips',
				        ],
				    	'imageUpload' => Url::to(['image-upload']),
						'imageManagerJson' => Url::to(['images-get']),
					],
				],
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
