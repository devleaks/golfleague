<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\StartSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Starts';
?>
<div class="start-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel'=>[
	        'heading' => '<h4>'.$this->title.'</h4>',
			'footer' => Html::a(Yii::t('igolf', 'Add Start'), ['start/add', 'id' => $competition->id], ['class' => 'btn btn-primary']),
	    ],
		'export' => false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            // 'competition_id',
            [
				'attribute' => 'gender',
				'value' => function($model, $key, $index, $widget) {
					return Yii::t('igolf', $model->gender);
				}
			],
            'age_min',
            'age_max',
            'handicap_min',
            'handicap_max',
            'tees.name',
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}',
				'controller' => 'start',
			],
        ],
    ]); ?>

</div>
