<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Points');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="point-index">

    <h1>    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('igolf', 'Create {modelClass}', [

    'modelClass' => 'Point',
]), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
            'rule_id',
            'position',
            'points',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>

</div>
