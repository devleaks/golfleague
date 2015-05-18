<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ScorecardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Scorecards');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scorecard-index">

    <h1>    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('igolf', 'Create {modelClass}', [

    'modelClass' => 'Scorecard',
]), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
            'match_id',
            'golfer_id',
            'tees',
            'note',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>

</div>
