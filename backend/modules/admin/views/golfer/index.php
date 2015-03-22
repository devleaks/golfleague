<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GolferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Golfers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="golfer-index">

    <h1>    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('igolf', 'Create {modelClass}', [

    'modelClass' => 'Golfer',
]), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
                'label' => Yii::t('igolf', 'Account'),
                'value' => function ($data) {
                    return isset($data->user) ? $data->user->username : 'None';
                },
            ],
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}'
			],
        ],
    ]); ?>

</div>
