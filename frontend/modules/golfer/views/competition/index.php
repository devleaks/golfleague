<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CompetitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Competitions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('list_registration', [
			'title' => Yii::t('golf', 'Open for Registration'),
	        'dataProvider' => $registrationProvider,
	        'filterModel' => $registrationSearch,
	]) ?>

    <?= $this->render('list_start', [
			'title' => Yii::t('golf','Played Soon'),
	        'dataProvider' => $startProvider,
	        'filterModel' => $startSearch,
	]) ?>

    <?= $this->render('list_result', [
			'title' => Yii::t('golf', 'Scores'),
	        'dataProvider' => $resultProvider,
	        'filterModel' => $resultSearch,
	]) ?>

    <?= $this->render('list_result', [
			'title' => Yii::t('golf', 'Standings'),
	        'dataProvider' => $result2Provider,
	        'filterModel' => $result2Search,
	]) ?>

    <?= $this->render('list_planned', [
			'title' => Yii::t('golf','Planned'),
	        'dataProvider' => $planProvider,
	        'filterModel' => $planSearch,
	]) ?>

    <?= $this->render('list_closed', [
			'title' => Yii::t('golf','Archive'),
	        'dataProvider' => $closedProvider,
	        'filterModel' => $closedSearch,
	]) ?>

</div>
