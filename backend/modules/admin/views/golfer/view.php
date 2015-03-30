<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\Facility;
use common\models\Golfer;
use common\models\User;
use yii2mod\selectize\Selectize;

/* @var $this yii\web\View */
/* @var $model common\models\Golfer */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Golfers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="golfer-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.$model->name.'</h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
            //'id',
            'name',
            'email:email',
            'phone',
            'handicap',
            [
				'attribute' => 'gender',
				'type' => DetailView::INPUT_RADIO_LIST,
				'items' => [''=>Yii::t('igolf', 'Unspecified')]+Golfer::getLocalizedConstants('GENDER_')
			],
            [
				'attribute' => 'hand',
				'type' => DetailView::INPUT_RADIO_LIST,
				'items' => [''=>Yii::t('igolf', 'Unspecified')]+Golfer::getLocalizedConstants('HAND_')
			],
            'homecourse',
			[
				'attribute' => 'facility_id',
                'value'=>isset($model->facility) ? $model->facility->name : '',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => ['' => 'Select home course...'] + ArrayHelper::map(Facility::find()->where(['>', 'id', 0])->asArray()->all(), 'id', 'name'),
				'widgetOptions' => [
					//'class' => Selectize::className(),
			        'pluginOptions' => [
			            'items' => ArrayHelper::map(Facility::find()->where(['>', 'id', 0])->asArray()->all(), 'id', 'name'),
			        ]
			    ]
			],
            [
                'attribute'=>'user_id',
                'label'=>'User',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => ArrayHelper::map([''=>'']+User::find()->orderBy('username')->asArray()->all(), 'id', 'username'),
                'value'=>isset($model->user) ? $model->user->username : 'None',
            ],
        ],
    ]) ?>

</div>
