<?php

use common\models\Competition;
use common\models\Registration;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = $competition->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-index">

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
	                return  $model->golfer->name;
	            },
				'noWrap' => true,
			],
			'thru' => [
				'type' => TabularForm::INPUT_TEXT,
			],
			'score' => [
				'type' => TabularForm::INPUT_TEXT,
			],
			'score_net' => [
				'type' => TabularForm::INPUT_TEXT,
			],
			'stableford' => [
				'type' => TabularForm::INPUT_TEXT,
			],
			'stableford_net' => [
				'type' => TabularForm::INPUT_TEXT,
			],
			'points' => [
				'type' => TabularForm::INPUT_STATIC,
			],
			'position' => [
				'type' => TabularForm::INPUT_STATIC,
			],
			'card_status' => [
				'type' => TabularForm::INPUT_DROPDOWN_LIST,
				'items' => Registration::getLocalizedConstants('CARD_'),
				/* value => RESULTS ? */
			],
			'status' => [
				'type' => TabularForm::INPUT_DROPDOWN_LIST,
				'items' => Registration::getLocalizedConstants('STATUS_'),
				/* value => RESULTS ? */
			],
        ],
    ]); ?>

	<?php ActiveForm::end(); ?>
	
</div>