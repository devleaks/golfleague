<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\Competition;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Competition Rules');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rule-index">

    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('igolf', 'Create Rule'), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

//            'id',
            'name',
            'description',
            [
				'attribute' => 'competition_type',
				'filter' => Competition::getLocalizedConstants('TYPE_'),
                'value' => function($model, $key, $index, $widget) {
                    return Yii::t('igolf', $model->competition_type);
                },
            ],
            // 'note',
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}'
			],
        ],
    ]); ?>

</div>
