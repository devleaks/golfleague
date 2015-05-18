<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Flights');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="flight-index">

    <h1>    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('igolf', 'Create {modelClass}', [

    'modelClass' => 'Flight',
]), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
            'match_id',
            'position',
            'note',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>

</div>
