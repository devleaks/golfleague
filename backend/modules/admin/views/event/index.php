<?php

use common\models\League;
use common\models\User;

use kartik\grid\GridView;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Events');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-cutlery"></i> '.Html::encode($this->title).' </h3>',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          	
//			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

			[
				'attribute' => 'league_id',
				'filter' => ArrayHelper::map(League::find()->asArray()->all(), 'id', 'name'),
				'visible' => Yii::$app->user->identity->isA(User::ROLE_ADMIN),
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->league ? $model->league->name : '';
	            },
			],
            'name',
            'event_start',
            'event_end',
            'event_type',
            // 'description',
            // 'status',
            // 'created_at',
            // 'updated_at',

            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}'
			],
        ],
    ]); ?>

</div>
