<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use kartik\widgets\TimePicker;
use kartik\widgets\TouchSpin;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('igolf', 'Flights for ').$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div id="feedback"></div>

<div class="flight-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_list', [
		'competition' => $model,
		'dataProvider' => new ActiveDataProvider([
			'query' => $model->getFlights()
		]),
	]) ?>
	
</div>