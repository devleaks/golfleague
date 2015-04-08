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
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div id="feedback"></div>

<div class="flight-index">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<p>Drag and drop golfers between flights.</p>
	<p>Drop golfer on dashed flight to create a new one.</p>
	<p>Flight framed in green is full (4 golfers).</p>
	<p>Reorder flights by moving them up and down the list.</p>
	<p>Set start time of first flight and time between 2 flights.</p>
	<p>Save flights or Reset to restart new fresh list. Select flight distribution algorithm from popup list.</p>
	<p>Publish final flights when done.</p>
</div>

<div class="gl-infoline">
<label class="control-label">First flight time</label> 
<div style='width: 140px; display: inline-block;'>
<?= TimePicker::widget([
	'name' => 'GLtimeStart',
	'options' => ['id' => 'GLtimeStart'],
	'pluginOptions' => [
		'defaultTime' => substr($competition->start_date, 11, 5),
		'minuteStep' => 1,
		'showMeridian' => false
	]
]); ?>
</div>

<label class="control-label">Flight time</label>
<div style='width: 180px; display: inline-block;'>
<?= TouchSpin::widget([
    'name' => 'GLdeltaStart',
    'options' => ['id' => 'GLdeltaStart'],
	'pluginOptions' => ['postfix' => 'min', 'initval' => 10]
]); ?>
</div>

<label class="control-label">Competition start</label> 
<?= $competition->start_date ?>.
</div>

<ul id="flight-case">

    <?php // each flight
	foreach($flights as $flight) {
		echo '<li>';
		echo $this->render('flight', [
			'flight' => $flight,
		]);
		echo '</li>';
	}
	// new flight ?>
	<li>
		<ul id="flight-new" class="flight new">
		</ul>
	</li>

</ul>

<?php $form = ActiveForm::begin(['id' => 'flights-form']); ?>
<?= Html::hiddenInput( 'flights', null, ['id' => 'savedflights'] )?>
<?= Html::submitButton(Yii::t('igolf', 'Save flights'), ['class' => 'btn btn-success']) ?>



        <div class="btn-group">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
            <?= Yii::t('igolf', 'Restart flights') ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <?php
                    echo '<li>'.Html::a(Yii::t('igolf', 'Reset Chronological Order'), ['flight/reset', 'competition_id' => $competition->id]).'</a></li>';
                ?>
            </ul>
        </div>

<?= Html::a(Yii::t('igolf', 'Publish Flights'), ['flight/publish', 'id' => $competition->id], ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>

</div>