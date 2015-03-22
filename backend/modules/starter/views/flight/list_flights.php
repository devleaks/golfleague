<?php

use yii\helpers\Html;
use yii\grid\GridView;
use devleaks\golfleague\assets\FlightsAsset;
use yii\widgets\ActiveForm;
use kartik\widgets\TimePicker;
use kartik\widgets\TouchSpin;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
FlightsAsset::register($this);

$this->title = Yii::t('igolf', 'Flights for ').$competition->name;
$this->params['breadcrumbs'][] = $this->title;

?>
<div id="feedback"></div>

<div class="flight-index">

    <h1><?= Html::encode($this->title) ?></h1>

<label class="control-label">Competition start</label> 
<?= $competition->start_date ?>.


<ul id="flights">

    <?php // each flight
	foreach($flights->each() as $flight) {
		echo '<li>';
		echo $this->render('detail_flight', [
			'flight' => $flight,
		]);
		echo '</li>';
	}
	?>
	
</ul>

</div>