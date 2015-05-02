<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\widgets\TimePicker;
use kartik\widgets\TouchSpin;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('igolf', 'Flights for ').$competition->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div id="feedback"></div>

<div class="flight-index">

    <h1><?= Html::encode($this->title) ?></h1>

<label class="control-label">Competition start:</label> 
<strong><?= Yii::$app->formatter->asDate($competition->start_date) ?></strong>.


<ul id="flights">

    <?php // each flight
	$number = 1;
	foreach($flights->each() as $flight) {
		echo '<li>';
		echo $this->render('detail_flight', [
			'flight' => $flight,
			'number' => $number++,
		]);
		echo '</li>';
	}
	?>
	
</ul>

</div>