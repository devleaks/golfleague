<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.Yii::t('igolf', $model->competition_type).' '.$model->name.'</h3>',
			'headingOptions' => [
				'template' => '{title}'
			],
	    ],
        'attributes' => [
            'description',
            [
                'attribute'=>'parent_id',
                'value'=> $model->parent ? $model->parent->name : '',
            ],
            [
                'attribute'=>'course_id',
                'value'=> $model->course ? $model->course->name : '',
            ],
            'holes',
            [
                'attribute'=>'rule_id',
                'value'=> $model->rule ? $model->rule->name : '',
            ],
            'start_date',
            [
                'attribute'=>'status',
                'label'=>'Status',
                'value'=> Yii::t('igolf', $model->status),
            ],
            'updated_at',
        ],
    ]) ?>

	<?= $this->render('_registrations', [
		'competition' => $model,
		'dataProvider' => new ActiveDataProvider([
			'query' => $model->getRegistrations(),
		]),		
	])?>

</div>
