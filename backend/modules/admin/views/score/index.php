<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ScoreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Scores');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="score-index">

    <h1>    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('igolf', 'Create {modelClass}', [

    'modelClass' => 'Score',
]), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'scorecard_id',
            'hole_id',
            'score',
            'putts',
            'penalty',
            // 'sand',
            // 'note',
            // 'drive',
            // 'regulation',
            // 'approach',
            // 'putt',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
