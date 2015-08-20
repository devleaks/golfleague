<?php

use common\widgets\Matchboard;
use kartik\detail\DetailView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.$model->getFullName().' <small>('.Yii::t('golf', $model->competition_type).')</small></h3>',
			'headingOptions' => [
				'template' => '{title}'
			],
	    ],
        'attributes' => [
            'description',
            [
                'attribute'=>'course_id',
                'value'=> $model->course ? $model->course->name : '',
            ],
            'holes',
            [
                'attribute'=>'rule_id',
                'value'=> $model->rule ? $model->rule->name : '',
            ],
            [
                'attribute'=>'start_date',
                'value'=> $model->start_date ? $model->start_date : $model->getStartDate(),
				'format' => 'datetime',
            ],
            [
                'attribute'=>'status',
                'label'=>Yii::t('golf', 'Status'),
                'value'=> Yii::t('golf', $model->status),
            ],

        ],
    ]) ?>

    <?= Matchboard::widget([
		'match' => $model,
		'options' => [
			Matchboard::LENGTH => true,
			Matchboard::SI => true,
			Matchboard::PAR => true,
			Matchboard::ROUNDS => true,
			Matchboard::TODAY => true,
			Matchboard::TO_PAR => true,
			Matchboard::COLOR => true,
			Matchboard::HOLES => true,
			Matchboard::UPS => 'U',
			Matchboard::DOWNS => 'D',
			Matchboard::ALLSQUARE => 'AS',
		]
    ]) ?>

</div>
