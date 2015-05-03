<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CompetitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Competitions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= $this->render('_registration', [
			'title' => Yii::t('igolf', 'Registration'),
	        'dataProvider' => $registrationProvider,
	        'filterModel' => $registrationSearch,
	]) ?>

    <?= $this->render('_start', [
			'title' => Yii::t('igolf','Start'),
	        'dataProvider' => $startProvider,
	        'filterModel' => $startSearch,
	]) ?>

    <?= $this->render('_ready', [
			'title' => Yii::t('igolf','Ready'),
	        'dataProvider' => $readyProvider,
	        'filterModel' => $readySearch,
	]) ?>

    <?= $this->render('_planned', [
			'title' => Yii::t('igolf','Planned'),
	        'dataProvider' => $planProvider,
	        'filterModel' => $planSearch,
	]) ?>

</div>
