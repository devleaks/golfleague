<?php

use common\models\Competition;
use common\models\Registration;
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
$this->title = $competition->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-index">

	<?php $form = ActiveForm::begin(); ?>

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
                'label' => Yii::t('igolf', 'Golfer'),
                'value' => function($model, $key, $index, $widget) {
                    return  $model->golfer->name;
                },
			],
            [
                'attribute' => 'card_status',
                'value' => function($model, $key, $index, $widget) {
                	return Yii::t('igolf', $model->card_status);
                },
				'filter' => Registration::getLocalizedConstants('CARD_'),
            ],
            [
                'attribute' => 'status',
                'value' => function($model, $key, $index, $widget) {
                	return Yii::t('igolf', $model->status);
                },
				'filter' => Registration::getLocalizedPostCompetitionStatuses(),
            ],
			[
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{scorecard}',
	            'buttons' => [
	                'scorecard' => function ($url, $model) {
						$url = Url::to(['scorecard/update', 'id' => $model->id]);
	                    return Html::a(Icon::show('invoice', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('igolf', 'Scorecard'),
	                    ]);
	                },
				]
	        ],
		]
    ]); ?>

	<?php ActiveForm::end(); ?>
	
</div>