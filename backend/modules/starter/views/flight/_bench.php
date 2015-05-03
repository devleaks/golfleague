<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\assets\FlightsAsset;
use yii\widgets\ActiveForm;
use kartik\widgets\TimePicker;
use kartik\widgets\TouchSpin;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<li>
	<ul class="flight bench">
	    <?php // each registration not in flight yet
		foreach($registrations->each() as $registration) {
			$golfer = $registration->golfer;
			$teesColor = isset($registration->tees->color) ? $registration->tees->color : 'black';
			echo '<li id="registration-'.$registration->id.'" class="golfer"  data-handicap="'.$golfer->handicap.'">'.$golfer->name.' ('.
				'<span class="glyphicon glyphicon-filter" style="color: '.$teesColor.';"></span> '.$golfer->handicap.')</li>';
		} ?>
	</ul>
</li>
