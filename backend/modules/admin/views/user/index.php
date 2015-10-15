<?php

use common\models\League;

use kartik\grid\GridView;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GolferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Golfers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="golfer-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> '.Html::encode($this->title).' </h3>',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          	
            'showFooter'=>false
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
				'attribute' => 'username',
            ],
            [
				'label' => Yii::t('golf', 'Golfer'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->golfer ? $model->golfer->name : '';
                },
            ],
            'email:email',
            [
				'attribute' => 'role',
				'filter' => Yii::$app->golfleague->league_roles,
            ],
            [
				'label' => Yii::t('golf', 'League'),
				'attribute' => 'league_id',
				'filter' => ArrayHelper::map(League::find()->asArray()->all(), 'id', 'name'),
                'value' => function ($model, $key, $index, $widget) {
                    return $model->league ? $model->league->name : '';
                },
            ],
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view}'
			],
        ],
    ]); ?>

</div>
