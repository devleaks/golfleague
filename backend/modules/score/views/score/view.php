<?php

use common\widgets\Scorecard;
use kartik\detail\DetailView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $scorecard common\models\Scorecard */

$this->title = $scorecard->registration->golfer->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = ['label' => $scorecard->registration->competition->name, 'url' => ['competition', 'id' => $scorecard->registration->competition_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scorecard-view">

    <?= DetailView::widget([
        'model' => $scorecard->registration->competition,
		'panel'=>[
	        'heading' => '<h3>'.$scorecard->registration->competition->getFullName().' <small>('.Yii::t('igolf', $scorecard->registration->competition->competition_type).')</small></h3>',
			'headingOptions' => [
				'template' => '{title}'
			],
	    ],
        'attributes' => [
            'description',
            [
                'attribute'=>'course_id',
                'label'=>'Course',
                'value'=> $scorecard->registration->competition->course ? $scorecard->registration->competition->course->name : '',
            ],
            'holes',
            [
                'attribute'=>'rule_id',
                'label'=>'Rules',
                'value'=> $scorecard->registration->competition->rule ? $scorecard->registration->competition->rule->name : '',
            ],
            [
                'attribute'=>'status',
                'label'=>'Scorecard Status',
                'value'=> Yii::t('igolf', $scorecard->status),
            ],
        ],
    ]) ?>

    <?= Scorecard::widget([
		'scorecard' => $scorecard,
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
