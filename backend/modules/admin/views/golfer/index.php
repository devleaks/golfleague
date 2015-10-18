<?php

use common\models\League;
use common\models\User;

use kartik\grid\GridView;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
//			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
        'columns' => [
			[
				'attribute' => 'league_id',
				'filter' => ArrayHelper::map(League::find()->asArray()->all(), 'id', 'name'),
				'visible' => Yii::$app->user->identity->isA(User::ROLE_ADMIN),
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->league ? $model->league->name : '';
	            },
			],
            [
				'attribute' => 'name',
				'noWrap' => true,
            ],
            'email:email',
            'phone',
            'handicap',
            // 'gender',
            // 'hand',
            'homecourse',
            [
				'attribute' => 'user_id',
                'label' => Yii::t('golf', 'Account'),
                'value' => function($model, $key, $index, $widget) {
                    return isset($model->user) ? $model->user->username : null;
                },
            ],
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}'
			],
        ],
    ]); ?>

</div>
