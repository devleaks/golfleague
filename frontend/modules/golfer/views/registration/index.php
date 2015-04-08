<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Registration');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="season-index">

	<?= $this->render('_registrations', [
		'searchModel'  => $registrationSearchModel,
		'dataProvider' => $registrationDataProvider,
	])?>

    <h2><?= Html::encode(Yii::t('igolf', 'Competitions')) ?></h2>

	<?= $this->render('_matches', [
		'searchModel'  => $matchesSearchModel,
		'dataProvider' => $matchesDataProvider,
	])?>

	<?= $this->render('_tournaments', [
		'searchModel'  => $tournamentsSearchModel,
		'dataProvider' => $tournamentsDataProvider,
	])?>

	<?= $this->render('_seasons', [
		'searchModel'  => $seasonsSearchModel,
		'dataProvider' => $seasonsDataProvider,
	])?>

</div>
