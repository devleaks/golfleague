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
$this->title = $scorecard->player->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = ['label' => $scorecard->registration->competition->name, 'url' => ['competition', 'id' => $scorecard->registration->competition_id]];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['view', 'id' => $scorecard->id]];
$this->params['breadcrumbs'][] = Yii::t('golf', 'Update');
?>
<div class="scorecard-update">

	<?php $form = ActiveForm::begin(); ?>

     <?= TabularForm::widget([
		'form' => $form,
        'dataProvider' => $dataProvider,
		'gridSettings'=> [
			'panel'=>[
		        'heading' => '<h4>'.$this->title.'</h4>',
				'before' => Yii::t('golf', 'Competition rule: {0}.', $scorecard->registration->competition->rule->getLabel()),
				'footer' => Html::submitButton('Save', ['class'=>'btn btn-primary']).' '.
							Html::a(Yii::t('golf', 'Publish'), Url::to(['publish', 'id' => $scorecard->id]), ['class'=>'btn btn-success']),
		    ],
		],
		'serialColumn' => [],
		'actionColumn' => false,
		'checkboxColumn' => false,
        'attributes' => [
			'allowed' => [
				'type' => TabularForm::INPUT_STATIC,
	            'value' => function($model, $key, $index, $widget) {
	                return  $model->hole->par .str_repeat(' •',$model->allowed);
	            },
			],
			'score' => [
				'type' => function($model, $key, $index, $widget) {
				    return  ( $model->scorecard->registration->competition->rule->source_type != 'score' ) ? TabularForm::INPUT_STATIC : TabularForm::INPUT_TEXT;
				},
			],
			'score_net' => [
				'type' => function($model, $key, $index, $widget) {
				    return  ( $model->scorecard->registration->competition->rule->source_type != 'score_net' ) ? TabularForm::INPUT_STATIC : TabularForm::INPUT_TEXT;
				},
			],
			'stableford' => [
				'type' => function($model, $key, $index, $widget) {
				    return  ( $model->scorecard->registration->competition->rule->source_type != 'stableford' ) ? TabularForm::INPUT_STATIC : TabularForm::INPUT_TEXT;
				},
			],
			'stableford_net' => [
				'type' => function($model, $key, $index, $widget) {
				    return  ( $model->scorecard->registration->competition->rule->source_type != 'stableford_net' ) ? TabularForm::INPUT_STATIC : TabularForm::INPUT_TEXT;
				},
			],
			'points' => [
				'type' => function($model, $key, $index, $widget) {
				    return  ( $model->scorecard->registration->competition->rule->source_type != 'points' ) ? TabularForm::INPUT_STATIC : TabularForm::INPUT_TEXT;
				},
			],
			'putts' => [
				'type' => TabularForm::INPUT_TEXT,
			],
			'teeshot' => [
				'type' => TabularForm::INPUT_DROPDOWN_LIST,
				'items' => Score::getLocalizedConstants('TARGET_'),
			],
			'teeshot_length' => [
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