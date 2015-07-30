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
	        'heading' => '<h4>'.$this->title.'</h4>',
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
			'flight.id',
			'team.id',
            [
                'attribute' => 'status',
                'value' => function($model, $key, $index, $widget) {
                	return Yii::t('golf', $model->status);
                },
				'filter' => Registration::getLocalizedPreCompetitionStatuses(),
            ],
        ],
    ]); ?>

</div>