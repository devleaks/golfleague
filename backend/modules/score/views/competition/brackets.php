<?php

use common\widgets\Brackets;
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

    <?= Brackets::widget([
		'competition' => $model,
		'options' => [
			Brackets::LENGTH => true,
			Brackets::SI => true,
			Brackets::PAR => true,
			Brackets::ROUNDS => true,
			Brackets::TODAY => true,
			Brackets::TO_PAR => true,
			Brackets::COLOR => true,
			Brackets::HOLES => true,
			Brackets::UPS => 'U',
			Brackets::DOWNS => 'D',
			Brackets::ALLSQUARE => 'AS',
		]
    ]) ?>

</div>
