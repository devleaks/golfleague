<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use kartik\icons\Icon;

Icon::map($this); 

/* @var $this yii\web\View */
/* @var $title String */
/* @var $searchModel common\models\CompetitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="competition-list">

<div class="box box-success">
    <div class="box-header with-border">
          <h3 class="box-title"><i class="glyphicon glyphicon-th-list"></i> <?= Html::encode($title) ?></h3>
    </div><!-- /.box-header -->
    <div class="box-body">

	    <?= GridView::widget([
	        'dataProvider' => $dataProvider,
			'panel' => false,
	        'columns' => [
	            [
	                'label' => Yii::t('golf', 'Competition'),
	                'value' => function($model, $key, $index, $widget) {
	                    return Yii::t('golf', $model->competition_type);
	                },
	            ],
	            'name',
	            'description',
				[
					'attribute' => 'course_id',
					'value' => function ($model, $key, $index, $widget) {
						return $model->course ? $model->course->name : '';
					}
				],
				[
					'attribute' => 'start_date',
					'format' => 'datetime',
					'value' => function ($model, $key, $index, $widget) {
						return new DateTime($model->start_date);
					}
				],
				[
					'attribute' => 'registration_end',
					'format' => 'raw',
					'value' => function ($model, $key, $index, $widget) {
						return '<span class="'.($model->registration_end < date('Y-m-d H:i:s') ? 'text-danger' : '').'">'.
									Yii::$app->formatter->asDateTime(new DateTime($model->registration_end)) . '</span';
					},
				],
	             'status',
	            $actionButtons,
	        ],
	    ]); ?>

    </div><!-- /.box-body -->
    <div class="box-footer clearfix">
        <!-- other markup -->
    </div>
</div>

</div>
