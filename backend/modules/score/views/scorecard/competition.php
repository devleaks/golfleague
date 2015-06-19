<?php

use common\models\Competition;
use common\models\Registration;
use common\models\Scorecard;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = $competition->getFullName();
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;

$input_decimal = [
	'type' => function($model, $key, $index, $widget) {
	    return  $model->hasDetails() ? TabularForm::INPUT_STATIC : TabularForm::INPUT_TEXT;
	},
	'columnOptions' => ['width' => '100px'],
];

$apply_rule = in_array($competition->competition_type, [Competition::TYPE_TOURNAMENT, Competition::TYPE_SEASON])
	? Html::a(Yii::t('igolf', 'Compute'), Url::to(['competition/apply', 'id' => $competition->id]), ['class'=>'btn btn-primary'])
	: '';
	
?>
<div class="scorecard-index">

	<?php $form = ActiveForm::begin(); ?>

     <?= TabularForm::widget([
		'form' => $form,
        'dataProvider' => $dataProvider,
		'gridSettings'=> [
			'panel'=>[
		        'heading' => '<h4>'.$this->title.'</h4>',
				'footer' => Html::submitButton('Save', ['class'=>'btn btn-primary']).' '.$apply_rule.' '.
							Html::a(Yii::t('igolf', 'Publish'), Url::to(['publish', 'id' => $competition->id]), ['class'=>'btn btn-success'])
							,
		    ],
		],
		'serialColumn' => false,
		'actionColumn' => false,
		'checkboxColumn' => false,
        'attributes' => [
			'id' => [
				'type' => TabularForm::INPUT_HIDDEN,
				'columnOptions' => [
					'visible' => false,
				]
			],
			'golfer_name' => [
				'type' => TabularForm::INPUT_STATIC,
	            'value' => function($model, $key, $index, $widget) {
	                return  $model->registration->golfer->name;
	            },
				'noWrap' => true,
			],
			'golfer_hdcp' => [
				'type' => TabularForm::INPUT_STATIC,
            	'label'=>Yii::t('igolf', 'Handicap'),
	            'value' => function($model, $key, $index, $widget) {
	                return  $model->registration->golfer->handicap;
	            },
				'noWrap' => true,
			],
			'thru' => $input_decimal,
			'score' => $input_decimal,
			'score_net' => $input_decimal,
			'stableford' => $input_decimal,
			'stableford_net' => $input_decimal,
			'points' => $input_decimal,
			'status' => [
				'type' => TabularForm::INPUT_DROPDOWN_LIST,
				'items' => Scorecard::getLocalizedConstants('STATUS_'),
			],
        ],
    ]); ?>

	<?php ActiveForm::end(); ?>
	
</div>