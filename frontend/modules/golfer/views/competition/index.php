<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CompetitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golfleague', 'Competitions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= $this->render('list_registration', [
			'title' => Yii::t('golfleague', 'Open for Registration'),
	        'dataProvider' => $registrationProvider,
	        'filterModel' => $registrationSearch,
	]) ?>

    <?= $this->render('list_start', [
			'title' => Yii::t('golfleague','Played Soon'),
	        'dataProvider' => $startProvider,
	        'filterModel' => $startSearch,
	]) ?>

    <?= $this->render('list_result', [
			'title' => Yii::t('golfleague', 'Scores'),
	        'dataProvider' => $resultProvider,
	        'filterModel' => $resultSearch,
	]) ?>

    <?= $this->render('list_result', [
			'title' => Yii::t('golfleague', 'Standings'),
	        'dataProvider' => $result2Provider,
	        'filterModel' => $result2Search,
	]) ?>

    <?= $this->render('list_planned', [
			'title' => Yii::t('golfleague','Planned'),
	        'dataProvider' => $planProvider,
	        'filterModel' => $planSearch,
	]) ?>

    <?= $this->render('list_closed', [
			'title' => Yii::t('golfleague','Archive'),
	        'dataProvider' => $closedProvider,
	        'filterModel' => $closedSearch,
	]) ?>

</div>
