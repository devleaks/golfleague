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

    <h1>    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('igolf', 'Create {modelClass}', [

    'modelClass' => 'Rule',
]), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'name',
            'description',
            [
				'attribute' => 'object_type',
				'label' => Yii::t('igolf', 'Uses Results Of'),
                'value' => function($model, $key, $index, $widget) {
                    return Yii::t('igolf', $model->object_type);
                },
				'filter' => Competition::getConstants('TYPE_'),
            ],
            [
				'attribute' => 'rule_type',
				'label' => Yii::t('igolf', 'Applies To'),
                'value' => function($model, $key, $index, $widget) {
                    return Yii::t('igolf', $model->rule_type);
                },
				'filter' => Competition::getConstants('TYPE_'),
            ],
            // 'note',
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}'
			],
        ],
    ]); ?>

</div>
