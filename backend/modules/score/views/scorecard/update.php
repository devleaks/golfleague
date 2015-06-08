<?php

use common\models\Score;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = $registration->golfer->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = ['label' => $registration->competition->name, 'url' => ['index', 'id' => $registration->competition_id]];
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
		'serialColumn' => [],
		'actionColumn' => false,
		'checkboxColumn' => false,
        'attributes' => [
			'score' => [
				'type' => TabularForm::INPUT_TEXT,
			],
			'putts' => [
				'type' => TabularForm::INPUT_TEXT,
			],
			'drive' => [
				'type' => TabularForm::INPUT_DROPDOWN_LIST,
				'items' => Score::getLocalizedConstants('TARGET_'),
			],
			'drive_length' => [
				'type' => TabularForm::INPUT_TEXT,
			],
			'regulation' => [
				'type' => TabularForm::INPUT_CHECKBOX,
			],
			'penalty' => [
				'type' => TabularForm::INPUT_TEXT,
			],
			'sand' => [
				'type' => TabularForm::INPUT_CHECKBOX,
			],
			'approach' => [
				'type' => TabularForm::INPUT_DROPDOWN_LIST,
				'items' => Score::getLocalizedConstants('TARGET_'),
			],
			'approach_length' => [
				'type' => TabularForm::INPUT_TEXT,
			],
			'putt_length' => [
				'type' => TabularForm::INPUT_TEXT,
			],
			'putt' => [
				'type' => TabularForm::INPUT_DROPDOWN_LIST,
				'items' => Score::getLocalizedConstants('TARGET_'),
			],
        ],
    ]); ?>

	<?php ActiveForm::end(); ?>
	
</div>