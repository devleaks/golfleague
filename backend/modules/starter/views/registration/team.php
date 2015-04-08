<?php

use devleaks\golfleague\assets\TeamsAsset;
use devleaks\golfleague\components\Competition;
use kartik\widgets\TimePicker;
use kartik\widgets\TouchSpin;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
TeamsAsset::register($this);

$this->title = Yii::t('igolf', 'Teams for ').$competition->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-index">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<p>Drag and drop golfers between teams.</p>
	<p>Drop golfer on dashed team to create a new team.</p>
	<p>Team framed in green is full (4 golfers).</p>
	<p>Reorder teams by moving them up and down the list.</p>
	<p>Save teams or Reset to restart new fresh list.</p>
	<p>Publish final teams when done.</p>
</div>

<ul id="flight-case">

	<li>
		<ul id="flight-source" class="flight source">
 			<div class="flight-info">Registered players not in teams</div>
   <?php
		if($noteam) {
			foreach($noteam as $registration) {
				$golfer = $registration->getGolfer()->one();
				$teesColor = isset($registration->getTees()->one()->color) ? $registration->getTees()->one()->color : 'black';
				echo '<li id="registration-'.$registration->id.'" class="golfer"  data-handicap="'.$golfer->handicap.'">'.$golfer->name.' ('.
					'<span class="glyphicon glyphicon-filter" style="color: '.$teesColor.';"></span> '.$golfer->handicap.')</li>';
			}
		}
	?>
		</ul>
	</li>

	<?php
		if(count($teams) > 0) {
			foreach($teams as $team) {
				echo '<li>';
				echo $this->render('flight', [
					'flight' => $team,
				]);
				echo '</li>';
			}
		}
	?>
	<li>
		<ul id="flight-new" class="flight new">
		</ul>
	</li>

</ul>

<?php $form = ActiveForm::begin(['id' => 'teams-form']); ?>
<?= Html::hiddenInput( 'teams', null, ['id' => 'savedteams'] )?>
<?= Html::submitButton(Yii::t('igolf', 'Save Teams'), ['class' => 'btn btn-success']) ?>

<?= Html::a(Yii::t('igolf', 'Delete All Teams and Start Over'), ['team/reset', 'competition_id' => $competition->id], ['class' => 'btn btn-warning']) ?>

<?= Html::a(Yii::t('igolf', 'Publish Teams'), ['team/publish', 'id' => $competition->id], ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>

</div>
<?php
$this->render('_teamscript',['id'=>$competition->id]);
