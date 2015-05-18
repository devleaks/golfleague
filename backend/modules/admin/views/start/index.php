<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\StartSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Starts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="start-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Start'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
            'gender',
            'age_min',
            'age_max',
            'handicap_min',
            // 'handicap_max',
            // 'tees_id',
            // 'created_at',
            // 'updated_at',
            // 'competition_id',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>

</div>
