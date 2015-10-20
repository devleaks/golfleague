<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use common\models\Registration;
use common\models\Competition;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="registration-list">

     <?= GridView::widget([
		'options' => ['id' => 'registration'],
        'dataProvider' => $dataProvider,
		'panel'=>[
	        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> '.Html::encode(Yii::t('golf', 'Registrations for {0}', $this->title)).' </h3>',
			'footer' => false,
	    ],
		'pjax' => true,
		'pjaxSettings' => [
	        'neverTimeout' => true,
        ],
		'export' => false,
        'columns' => [
            [
            	'attribute' => 'golfer_name',
                'label' => Yii::t('golf', 'Golfer'),
                'value' => function($model, $key, $index, $widget) {
                    return  $model->golfer->name;
                },
			],
			'tees.name',
            [
                'attribute' => 'status',
				'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
                'value' => function($model, $key, $index, $widget) {
                	return $model->makeLabel($model->status);
                },
				'filter' => Registration::getRegistrationStatusesFor(Registration::SC_PRE_COMPETITION, true),
            ],
        ],
    ]); ?>

</div>