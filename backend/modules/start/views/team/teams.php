<?php

use backend\assets\TeamsAsset;
use common\models\Competition;
use kartik\widgets\TimePicker;
use kartik\widgets\TouchSpin;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
TeamsAsset::register($this);

$this->title = Yii::t('igolf', 'Teams for ').$competition->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div id="feedback"></div>

<div class="team-index">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<p>Drag and drop golfers between teams.</p>
	<p>Drop golfer on dashed team to create a new one.</p>
	<p>Flight framed in green is full (maximum number of golfers per team reached).</p>
	<p>Save teams or Reset to restart new fresh list.</p>
</div>

<div class="row">
	<div class="col-lg-2">
		<label class="control-label">Competition start</label> 
		<p><strong><?= Yii::$app->formatter->asDate($competition->start_date) ?></strong></p>
	</div>

	<div class="col-lg-3">
		<label class="control-label">Team size</label>
		<?= TouchSpin::widget([
		    'name' => 'GLflightSize',
		    'options' => ['id' => 'GLflightSize'],
			'pluginOptions' => ['postfix' => 'golfers', 'initval' => 2, 'min' => 2, 'max' => 4],
			'pluginEvents' => [
				'change' => 'cleanUp',
			]
		]); ?>
	</div>

</div>

<ul id="flight-case">

	<li>
		<ul id="flight-new" class="flight bench">
		    <?php // each flight
		foreach($registrations->each() as $registration) {
			$golfer = $registration->golfer;
			$teesColor = isset($registration->tees->color) ? $registration->tees->color : 'black';
			echo '<li id="registration-'.$registration->id.'" class="golfer"  data-handicap="'.$golfer->handicap.'">'.$golfer->name.' ('.
				'<span class="glyphicon glyphicon-filter" style="color: '.$teesColor.';"></span> '.$golfer->handicap.')</li>';
			}
			// new flight ?>
		</ul>
	</li>

    <?php // each flight
	foreach($teams->each() as $team) {
		echo '<li>';
		echo $this->render('team', [
			'team' => $team,
		]);
		echo '</li>';
	}
	// new flight ?>
	<li>
		<ul id="flight-new" class="flight new">
		</ul>
	</li>

</ul>

<?php $form = ActiveForm::begin(['id' => 'teams-form']); ?>
<?= Html::hiddenInput( 'teams', null, ['id' => 'savedteams'] )?>
<?= Html::submitButton(Yii::t('igolf', 'Save teams'), ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_TEAM_INIT') ?>
maxSizeDefault = <?= $competition->rule->team ? $competition->rule->team : 2 ?>;
cleanUp();
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_TEAM_INIT'], yii\web\View::POS_READY);
