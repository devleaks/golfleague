<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GolferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Golfers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="golfer-index">

    <h1>    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('golf', 'Create {modelClass}', [

    'modelClass' => 'Golfer',
]), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
//          'id',
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
