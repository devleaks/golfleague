<?php

use common\widgets\Scoreboard;
use kartik\detail\DetailView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = Html::encode($model->getFullName()).' <small>('.Html::encode(Yii::t('golf', $model->competition_type)).')</small>';
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Html::encode($model->getFullName());
?>
<div class="competition-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> '.$this->title.' </h3>',
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

    <?= Scoreboard::widget([
		'competition' => $model,
		'options' => [
			Scoreboard::LENGTH => true,
			Scoreboard::SI => true,
			Scoreboard::PAR => true,
			Scoreboard::ROUNDS => true,
			Scoreboard::TODAY => true,
			Scoreboard::TO_PAR => true,
			Scoreboard::COLOR => true,
			Scoreboard::LEGEND => true,
		]
    ]) ?>

</div>
