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
	        'heading' => '<h3 class="box-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($title).'</h3>',
			'type' => ' box-success',
			'headingOptions' => ['class' => 'box-heading'],
			'footer' => false,
		],
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
            'course.name',
            'holes',
            'rule.name',
			[
				'attribute' => 'start_date',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->start_date);
				}
			],
            'status',
            $actionButtons,
        ],
    ]); ?>

</div>
