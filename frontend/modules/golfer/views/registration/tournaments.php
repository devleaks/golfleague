<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golfleague', 'Seasons');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="season-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Season Name'),
                'value' => function ($data) {
                    return $data->season->name;
                },
            ],

            'name',
            'description',

            ['class' => 'common\models\CompetitionGolferActionColumn'],
        ],
    ]); ?>

</div>
