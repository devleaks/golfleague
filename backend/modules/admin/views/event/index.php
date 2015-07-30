<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Events');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <h1>    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('golf', 'Create {modelClass}', [

    'modelClass' => 'Event',
]), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            // 'id',
            // 'object_type:ntext',
            // 'object_id',
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
