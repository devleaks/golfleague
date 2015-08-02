<?php

use common\models\Registration;

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="season-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel' => [
	        'heading' => '<h4>'.Yii::t('golf', 'Current Registrations').'</h4>',
		],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

	        [
	            'label' => Yii::t('golf', 'Competition Type'),
            	'attribute' => 'competition.competition_type',
			],
            'competition.name',
            'competition.start_date',
            [
                'label' => Yii::t('golf', 'Status of Registration'),
                'value' => function ($model, $key, $index, $widget) {
                        return Yii::t('golf', $model->status);
                }
            ],
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {approve}',
				'buttons' => [
	                'cancel' => function ($url, $model) {
						if($model->status == Registration::STATUS_REGISTERED) {
							$url = Url::to(['cancel', 'id' => $model->id]);
		                    return Html::a('<i class="glyphicon glyphicon-remove"></i>', $url, [
		                        'title' => Yii::t('golf', 'Cancel registration'),
		                    ]);
						}
						return '';
	                },
					'approve' => function ($url, $model) {
						if($model->status == Registration::STATUS_INVITED) {
							$url = Url::to(['approve', 'id' => $model->id]);
		                    return Html::a('<i class="glyphicon glyphicon-ok"></i>', $url, [
		                        'title' => Yii::t('golf', 'Approve'),
		                    ]);
						}
						return '';
	                },
				],
	            
			],
        ],
    ]); ?>

</div>
