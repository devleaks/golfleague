<?php

use backend\assets\FlightsAsset;
use common\models\Competition;
use common\models\Flight;

use kartik\widgets\TimePicker;
use kartik\widgets\TouchSpin;
use kartik\grid\GridView;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
FlightsAsset::register($this);

$this->title = Yii::t('golf', 'Flights for ').$competition->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div id="feedback"></div>

<div class="flight-index">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<p>Drag and drop golfers between flights.</p>
	<p>Drop golfer on dashed flight to create a new one.</p>
	<p>Flight framed in green is full (maximum nummber of golfers per flight reached).</p>
	<p>Reorder flights by moving them up and down the list.</p>
	<p>Set start time of first flight and time between 2 flights.</p>
	<p>Save flights or Reset to restart new fresh list. Select flight distribution algorithm from popup list.</p>
	<p>Publish final flights when done.</p>
</div>

<?php $form = ActiveForm::begin(['id' => 'flights-form']); ?>

<div class="row">
	<div class="col-lg-2">
		<label class="control-label">Competition start</label> 
		<p><strong><?= Yii::$app->formatter->asDate($competition->start_date) ?></strong></p>
	</div>

	<div class="col-lg-2">
		<label class="control-label">First flight start time</label> 
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


	<div class="col-lg-3">
		<label class="control-label">Flight time</label>
		<?= TouchSpin::widget([
		    'name' => 'GLdeltaStart',
		    'options' => ['id' => 'GLdeltaStart'],
			'pluginOptions' => ['postfix' => 'min', 'initval' => ($competition->flight_time ? $competition->flight_time : Competition::FLIGHT_TIME_DEFAULT), 'min' => 4, 'max' => 30]
		]); ?>
	</div>

	<div class="col-lg-3">
		<label class="control-label">Flight size</label>
		<?= TouchSpin::widget([
		    'name' => 'GLflightSize',
		    'options' => ['id' => 'GLflightSize'],
			'pluginOptions' => ['postfix' => 'golfers', 'initval' => 4, 'min' => 1, 'max' => $competition->flight_size ? $competition->flight_size : Competition::FLIGHT_SIZE_DEFAULT],
			'pluginEvents' => [
				'change' => 'cleanUp',
			]
		]); ?>
	</div>

</div>

<ul id="flight-case"
    data-competition="<?= $competition->id ?>"
	data-ismatch="<?= $competition->isMatchCompetition() ? '1' : '0' ?>"
	data-isteam="<?= $competition->isTeamCompetition() ? '1' : '0' ?>"
	data-teamsize="<?= $competition->rule->team_size ?>"
	>

    <?php // each flight
	foreach($competition->getFlights()->orderBy('position')->each() as $flight) {
		echo '<li>';
		echo $this->render('flight', [
			'flight' => $flight,
			'competition' => $competition,
		]);
		echo '</li>';
	}
	// new flight ?>
	<li>
		<ul id="flight-new" class="flight new panel panel-info">
			<div class="panel-heading handle-shiftbot"><span class="glyphicon glyphicon-star handle-shift"></span>Drop here to create a new flight.</div>
		</ul>
	</li>

</ul>

<ul class="exempted panel panel-warning">
	<div class="panel-heading"><span class="glyphicon glyphicon-warning-sign handle-shift"></span><?= Yii::t('golf', 'Exempted') ?></div>
<?php
if ($competition->isMatchCompetition()) {
	if($competition->isTeamCompetition()) {
		foreach($competition->getMatches()->each() as $m) {
			if($m->getOpponents()->count() < 2) {
				foreach($m->getOpponents()->each() as $o) {
					echo '<li class="golfer">'.$o->name.'</li>';
				}
			}
		}
	} else {
		foreach($competition->getRegistrationsNotIn(Flight::TYPE_FLIGHT)->each() as $r) {
			echo '<li class="golfer">'.$r->golfer->name.'</li>';
		}
	}
} ?>
</ul>

<?= Html::hiddenInput( 'flights', null, ['id' => 'savedflights'] )?>
<?= Html::submitButton(Yii::t('golf', 'Save flights'), ['class' => 'btn btn-success']) ?>



<div class="btn-group">
    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
    <?= Yii::t('golf', 'Restart flights') ?> <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <?php
            echo '<li>'.Html::a(Yii::t('golf', 'Reset Chronological Order'), ['flight/reset', 'id' => $competition->id]).'</a></li>';
        ?>
    </ul>
</div>

<?= Html::a(Yii::t('golf', 'Publish Flights'), ['flight/publish', 'id' => $competition->id], [
		'class' => 'btn btn-primary',
		'data' => [
    		'confirm' => Yii::t('golf', 'Are you sure you want to publish flights for this competition?'),
		],
]) ?>

<?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_FLIGHT_INIT') ?>
adjustDate();
cleanUp();
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_FLIGHT_INIT'], yii\web\View::POS_READY);
