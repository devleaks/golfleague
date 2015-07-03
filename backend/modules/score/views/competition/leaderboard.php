<?php

use common\widgets\Scoreboard;
use kartik\detail\DetailView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.$model->getFullName().' <small>('.Yii::t('igolf', $model->competition_type).')</small></h3>',
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
            'start_date',
            [
                'attribute'=>'status',
                'label'=>Yii::t('igolf', 'Status'),
                'value'=> Yii::t('igolf', $model->status),
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
