<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $title String */
/* @var $searchModel common\models\CompetitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div id="start" class="competition-list">

    <h2><?= Html::encode($title) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel'  => $filterModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => Yii::t('igolf', 'Competition'),
                'value' => function($model, $key, $index, $widget) {
                    return Yii::t('igolf', $model->competition_type);
                },
            ],
            'name',
            'description',
            'course_id',
            // 'holes',
            // 'rule_id',
             'start_date',
            // 'registration_begin',
             'registration_end',
            // 'handicap_min',
            // 'handicap_max',
            // 'age_min',
            // 'age_max',
            // 'gender',
             'status',
            // 'created_at',
            // 'updated_at',
            // 'parent_id',

            [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {update} {flight} {team} {tees}',
	            'buttons' => [
	                'flight' => function ($url, $model) {
						$url = Url::to(['flight/competition', 'id' => $model->id, 'sort' => 'position']);
	                    return Html::a('<i class="glyphicon glyphicon-play"></i>', $url, [
	                        'title' => Yii::t('store', 'Make Flights'),
	                    ]);
	                },
	                'team' => function ($url, $model) {
						$url = Url::to(['team/index', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-tag"></i>', $url, [
	                        'title' => Yii::t('store', 'Make Teams'),
	                    ]);
	                },
				],
			],
        ],
    ]); ?>

</div>
