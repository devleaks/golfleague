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
$this->title = Yii::t('igolf', 'Scorecards for competition «{0}»', $competition->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scorecard-index">

	<?php $form = ActiveForm::begin(); ?>

     <?= GridView::widget([
		'options' => ['id' => 'registration'],
        'dataProvider' => $dataProvider,
		'panel'=>[
	        'heading' => '<h4>'.$this->title.'</h4>',
			'footer' => false,
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
                'attribute' => 'status',
        		'label' => Yii::t('igolf', 'Scorecard Status'),
                'value' => function($model, $key, $index, $widget) {
                	return Yii::t('igolf', $model->status);
                },
				'filter' => Scorecard::getLocalizedConstants('STATUS_'),
            ],
            [
                'attribute' => 'status',
            	'label' => Yii::t('igolf', 'Registration Status'),
                'value' => function($model, $key, $index, $widget) {
                	return Yii::t('igolf', $model->status);
                },
				'filter' => Registration::getLocalizedPostCompetitionStatuses(),
            ],
			[
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {update} {delete}',
	        ],
		]
    ]); ?>

	<?php ActiveForm::end(); ?>
	
</div>