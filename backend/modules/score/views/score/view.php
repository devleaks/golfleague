<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\data\ActiveDataProvider;
use common\widgets\Scorecard;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = $model->registration->golfer->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = ['label' => $model->registration->competition->name, 'url' => ['competition', 'id' => $model->registration->competition_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scorecard-view">

    <?= DetailView::widget([
        'model' => $model->registration->competition,
		'panel'=>[
	        'heading' => '<h3>'.$model->registration->competition->getFullName().' <small>('.Yii::t('igolf', $model->registration->competition->competition_type).')</small></h3>',
			'headingOptions' => [
				'template' => '{title}'
			],
	    ],
        'attributes' => [
            'description',
            [
                'attribute'=>'course_id',
                'label'=>'Course',
                'value'=> $model->registration->competition->course ? $model->registration->competition->course->name : '',
            ],
            'holes',
            [
                'attribute'=>'rule_id',
                'label'=>'Rules',
                'value'=> $model->registration->competition->rule ? $model->registration->competition->rule->name : '',
            ],
            [
                'attribute'=>'status',
                'label'=>'Scorecard Status',
                'value'=> Yii::t('igolf', $model->status),
            ],
        ],
    ]) ?>

    <?= Scorecard::widget([
		'model' => $model,
		'options' => [
			Scorecard::LENGTH => true,
			Scorecard::SI => true,
			Scorecard::PAR => true,
			Scorecard::HOLES => true,
			Scorecard::FRONTBACK => true,
			Scorecard::COLOR => true,
			Scorecard::LEGEND => true,
			Scorecard::GROSS => true,
			Scorecard::ALLOWED => true,
			Scorecard::NET => true,
			Scorecard::TO_PAR => true,
			Scorecard::STABLEFORD => true,
			Scorecard::ALLOWED_ICON => '•',
		]
    ]) ?>

</div>
