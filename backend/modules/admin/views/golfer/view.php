<?php

use common\models\Facility;
use common\models\League;
use common\models\Golfer;
use common\models\User;

use kartik\detail\DetailView;

use machour\sparkline\Sparkline;
use yii2mod\selectize\Selectize;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Golfer */
$h = '';
if($hh = $model->getHandicapHistory()) {
	$h = Sparkline::widget([
	    'data' => $hh,
	    'clientOptions' => [
	        'type' => 'line',
	    ],
	]);
}

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Golfers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="golfer-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i>  '.Html::encode($this->title).'</h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
            //'id',
            'name',
            'email:email',
            'phone',
            [
				'attribute' => 'handicap',
				'value' => $model->handicap.' '.$h,
				'format' => 'raw',
			],
            [
				'attribute' => 'gender',
				'type' => DetailView::INPUT_RADIO_LIST,
				'items' => [''=>Yii::t('golf', 'Unspecified')]+Golfer::getLocalizedConstants('GENDER_')
			],
            [
				'attribute' => 'hand',
				'type' => DetailView::INPUT_RADIO_LIST,
				'items' => [''=>Yii::t('golf', 'Unspecified')]+Golfer::getLocalizedConstants('HAND_')
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
				'label' => Yii::t('golf', 'League'),
				'attribute' => 'league_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => [''=>''] + ArrayHelper::map(League::find()->asArray()->all(), 'id', 'name'),
	            'value' => $model->league ? $model->league->name : '',
				'displayOnly' => !Yii::$app->user->identity->isAdmin(),
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
