<?php

use backend\assets\MatchAsset;
use common\models\Registration;

use kartik\grid\GridView;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

MatchAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Matches for « {0} »', $competition->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['/start/competition']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<p>Drag and drop golfers between matches.</p>
	<p>Due to a bug, you have to manually move both golfers to swap them. (Will be fixed some day.)</p>
	<p>Save match or Reset to restart new fresh list. Select match algorithm from popup list to reset matches.</p>
	<p>Publish matches when done programming.</p>
</div>

<div class="alert alert-danger match-error">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<p>There is at least one match with three players.</p>
</div>

<ul class="competition" data-competition="<?= $competition->id ?>">
<?php
$count = 0;
foreach($matches as $match) {
	echo $this->render('_match', [
		'match' => $match,
	]);
	$count++;
}
/** We add matches to get a power of two and allow "byes" */
$maxmatches = pow(2.0, $competition->getLevel(Registration::STATUS_REGISTERED));
for($i = $count; $i < $maxmatches; $i++) {
	echo $this->render('_match-empty', [
		'match' => null,
	]);
}
?>
</ul>

<?php $form = ActiveForm::begin(['id' => 'matches-form']); ?>
<?= Html::hiddenInput( 'matches', null, ['id' => 'savedmatches'] )?>
<?= Html::button(Yii::t('golf', 'Save matches'), ['class' => 'btn btn-success matchFormSubmit']) ?>

<div class="btn-group">
    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
    <?= Yii::t('golf', 'Restart matches') ?> <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <?php
            echo '<li>'.Html::a(Yii::t('golf', 'Reset Chronological Registration Order'), ['match/reset', 'id' => $competition->id]).'</a></li>';
        ?>
    </ul>
</div>

<?= Html::a(Yii::t('golf', 'Publish Matches'), ['match/publish', 'id' => $competition->id], [
		'class' => 'btn btn-primary',
		'data' => [
    		'confirm' => Yii::t('golf', 'Are you sure you want to publish matches for this competition?'),
		],
]) ?>

<?php ActiveForm::end(); ?>

</div>