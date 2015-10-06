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

<<<<<<< HEAD
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
	    </div>
	</div>
=======
    <?= GridView::widget([
		/**/
		
		'panelTemplate' =>'
<div class="box {type}">
    {panelHeading}
    {panelBefore}
    {items}
    {panelAfter}
    {panelFooter}
</div>',
		'panelHeadingTemplate' => '
<div class="pull-right">
    {summary}
</div>
<h3 class="box-title">
    {heading}
</h3>
<div class="clearfix"></div>',
		'panelFooterTemplate' => '
<div class="kv-panel-pager">
    {pager}
</div>
{footer}
<div class="clearfix"></div>',

		/**/
        'dataProvider' => $dataProvider,
		'panel' => [
	        'heading' => '<h3 class="box-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($title).' </h3>',
			'type' => ' box-info',
			'headingOptions' => ['class' => 'box-heading'],
		],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
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
>>>>>>> origin/adminlte

</div>
