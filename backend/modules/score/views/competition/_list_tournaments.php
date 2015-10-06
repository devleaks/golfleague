<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $title String */
/* @var $searchModel common\models\CompetitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="competition-list">

	<div class="box box-info">
	    <div class="box-header with-border">
	          <h3 class="box-title"><i class="glyphicon glyphicon-th-list"></i> <?= Html::encode($title) ?></h3>
	    </div><!-- /.box-header -->
	    <div class="box-body">

		    <?= GridView::widget([
		        'dataProvider' => $dataProvider,
		        'columns' => [
		            ['class' => 'kartik\grid\SerialColumn'],
		            [
		                'label' => Yii::t('golf', 'Competition'),
		                'value' => function($model, $key, $index, $widget) {
		                    return Yii::t('golf', $model->competition_type);
		                },
		            ],
		            'name',
		            'description',
		            [
		                'label' => Yii::t('golf', 'Rule'),
		            	'attribute' => 'rule.name',
		            ],
		            // 'registration_begin',
		            // 'registration_end',
		            // 'handicap_min',
		            // 'handicap_max',
		            // 'age_min',
		            // 'age_max',
		            // 'gender',
		            'status',
		            // 'created_at',
		            // 'updated_at',
		            // 'parent_id',

		            $actionButtons,
		        ],
		    ]); ?>

	    	</div><!-- /.box-body -->
	    <div class="box-footer clearfix">
	        <!-- other markup -->
    	</div><!-- /.box-footer -->
	</div><!-- /.box -->

</div>
