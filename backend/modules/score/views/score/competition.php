<?php

use common\models\Competition;
use common\models\Registration;
use common\models\Scorecard;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

Icon::map($this, Icon::WHHG); 

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('golf', 'Scorecards for competition «{0}»', $competition->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scorecard-index">

     <?= GridView::widget([
		'options' => ['id' => 'registration'],
        'dataProvider' => $dataProvider,
		'panel'=>[
	        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> '.Html::encode($this->title).' </h3>',
			'footer' => Html::a(Yii::t('golf', 'Publish'), Url::to(['scorecard/publish', 'id' => $competition->id]), ['class'=>'btn btn-success'])
	    ],
		'export' => false,
        'columns' => [
            [
            	'attribute' => 'golfer_name',
                'label' => Yii::t('golf', 'Player'),
                'value' => function($model, $key, $index, $widget) {
                    return  $model->player->name;
                },
			],
            [
            	'label' => Yii::t('golf', 'Registration Status'),
				'filter' => Registration::getRegistrationStatusesFor(Registration::SC_POST_COMPETITION, true),
				'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
                'value' => function($model, $key, $index, $widget) {
                	return $model->registration ? $model->registration->makeLabel($model->registration->status) : '';
                },
            ],
            [
                'attribute' => 'status',
    			'label' => Yii::t('golf', 'Scorecard Status'),
				'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
                'value' => function($model, $key, $index, $widget) {
                	return $model->makeLabel($model->status);
                },
				'filter' => Scorecard::getLocalizedConstants('STATUS_'),
            ],
			[
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {update} {reset}',
	            'buttons' => [
	                'reset' => function ($url, $model) {
						if($model->hasDetails()) {
							$url = Url::to(['delete', 'id' => $model->id]);
		                    return Html::a('<i class="glyphicon glyphicon-remove"></i>', $url, [
		                        'title' => Yii::t('golf', 'Delete score details'),
								'data-confirm' => Yii::t('golf', 'Delete score details for this golfer?')
		                    ]);
						} else {
							return '<i class="glyphicon glyphicon-remove disabled"></i>';
						}
	                },
				],
	        ],
		]
    ]); ?>
	
</div>