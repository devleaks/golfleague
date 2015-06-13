<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PracticeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Practices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="practice-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel'=>[
	        'heading' => '<h4>'.$this->title.'</h4>',
			'footer' => Html::a(Yii::t('igolf', 'Create Practice Round'), ['create'], ['class' => 'btn btn-success']),
	    ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'course_id',
            [
				'attribute' => 'start_time',
                'format' => 'datetime',
				'noWrap' => true,
            ],
            'start_hole',
            'holes',
            'tees_id',
            'handicap',
            'status',
            // 'updated_at',
            'created_at',

			[
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {update} {delete}',
			],
        ],
    ]); ?>

</div>
