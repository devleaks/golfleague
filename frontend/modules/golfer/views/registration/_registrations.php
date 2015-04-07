<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golfleague', 'Current Registrations');
?>
<div class="season-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'competition.competition_type',
            'competition.name',
            'competition.start_date',
            [
                'label' => Yii::t('golfleague', 'Status'),
                'value' => function ($model, $key, $index, $widget) {
                        return Yii::t('golfleague', $model->status);
                    }
            ],
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view}'
			],
        ],
    ]); ?>

</div>
