<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Calendar'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-post-short">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.$model->name.'</h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
            'description',
            [
                'attribute'=>'event_type',
            ],
            [
                'attribute'=>'event_start',
				'format' => 'datetime',
				'value' => $model->event_start ? new DateTime($model->event_start) : '',
            ],
            [
                'attribute'=>'event_end',
				'format' => 'datetime',
				'value' => $model->event_end ? new DateTime($model->event_end) : '',
            ],
        ],
    ]) ?>

</div>