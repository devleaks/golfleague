<?php

use common\widgets\Scorecard;
use kartik\detail\DetailView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $scorecard common\models\Practice */

$this->title = $model->course->name.' » '.Yii::$app->formatter->asDateTime($model->start_time);
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Practice Rounds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scorecard-view">

    <?= Scorecard::widget([
		'scorecard' => $model->getScorecard(true),
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
		]
    ]) ?>

</div>
