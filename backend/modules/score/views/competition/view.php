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
            'id',
            [
                'attribute'=>'competition_type',
                'label'=>'Competition Type',
                'value'=> Yii::t('igolf', $model->competition_type),
            ],
            [
                'attribute'=>'parent_id',
                'label'=>'Competition',
                'value'=> $model->parent ? $model->parent->name : '',
            ],
            'name',
            'description',
            [
                'attribute'=>'course_id',
                'label'=>'Competition',
                'value'=> $model->course ? $model->course->name : '',
            ],
            'holes',
            [
                'attribute'=>'rule_id',
                'label'=>'Competition',
                'value'=> $model->rule ? $model->rule->name : '',
            ],
            'start_date',
            'registration_begin',
            'registration_end',
            'handicap_min',
            'handicap_max',
            'age_min',
            'age_max',
            [
				'attribute' => 'gender',
            	'value'=> Yii::t('igolf', $model->gender),
			],
            'max_players',
            [
                'attribute'=>'registration_special',
            	'value'=> Yii::t('igolf', $model->registration_special),
            ],
        	'registration_time',
            'flight_size',
			'flight_time',
            [
                'attribute'=>'status',
                'label'=>'Status',
                'value'=> Yii::t('igolf', $model->status),
            ],
            'created_at',
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
