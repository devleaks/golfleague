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

$static_if_has_details = function($model, $key, $index, $widget) {
    return  $model->hasDetails() ? TabularForm::INPUT_STATIC : TabularForm::INPUT_TEXT;
}

?>
<div class="scorecard-index">

	<?php $form = ActiveForm::begin(); ?>

     <?= TabularForm::widget([
		'form' => $form,
        'dataProvider' => $dataProvider,
		'gridSettings'=> [
			'panel'=>[
		        'heading' => '<h4>'.$this->title.'</h4>',
				'footer' => Html::submitButton('Save', ['class'=>'btn btn-primary']).' '.
							Html::a(Yii::t('igolf', 'Publish'), Url::to(['publish', 'id' => $competition->id]), ['class'=>'btn btn-success']),
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
			'thru' => [
				'type' => $static_if_has_details,
			],
			'score' => [
				'type' => $static_if_has_details,
			],
			'score_net' => [
				'type' => $static_if_has_details,
			],
			'stableford' => [
				'type' => $static_if_has_details,
			],
			'stableford_net' => [
				'type' => $static_if_has_details,
			],
			'points' => [
				'type' => $static_if_has_details,
			],
			'status' => [
				'type' => TabularForm::INPUT_DROPDOWN_LIST,
				'items' => Scorecard::getLocalizedConstants('STATUS_'),
				/* value => RESULTS ? */
			],
        ],
    ]); ?>

	<?php ActiveForm::end(); ?>
	
</div>