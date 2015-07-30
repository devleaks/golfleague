<?php

use common\models\Scorecard as ScorecardModel;
use common\widgets\Scorecard;
use kartik\detail\DetailView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $scorecard common\models\Scorecard */

$this->title = $scorecard->registration->golfer->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = ['label' => $scorecard->registration->competition->name, 'url' => ['competition', 'id' => $scorecard->registration->competition_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scorecard-view">

    <?= DetailView::widget([
        'model' => $scorecard->registration->competition,
		'panel'=>[
	        'heading' => '<h3>'.$scorecard->registration->competition->getFullName().' <small>('.Yii::t('golf', $scorecard->registration->competition->competition_type).')</small></h3>',
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
                'value'=> Yii::t('golf', $scorecard->status),
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
			Scorecard::ALLOWED => true,
			Scorecard::ALLOWED_ICON => '•',
			Scorecard::SCORE => true,
			Scorecard::SCORE_NET => true,
			Scorecard::STABLEFORD => true,
			Scorecard::STABLEFORD_NET => true,
			Scorecard::TO_PAR => true,
			Scorecard::TO_PAR_NET => true,
		],
/*		'colors' => [
			3 => 'black',
			2 => 'red',
			1 => 'orange',
			0 => 'white',
			-1 => 'green',
			-2 => 'blue',
			-3 => 'purple',
			-4 => 'grey'
		]*/
    ]) ?>

	<?php if(! in_array($scorecard->competition->status, [Competition::STATUS_COMPLETED, Competition::STATUS_CLOSED]) ): ?>
		<div class="clearfix"></div>
		<?= Html::a(Yii::t('golf', 'Update'), Url::to(['update', 'id' => $scorecard->id]), ['class' => 'btn btn-primary']) ?>
	<?php endif; ?>

</div>
