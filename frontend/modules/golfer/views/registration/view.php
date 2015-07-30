<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $competition common\models\Registration */

$this->title = $competition->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Registrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-view">

    <?= DetailView::widget([
        'model' => $competition,
		'panel'=>[
	        'heading' => '<h3>'.Yii::t('golf', $competition->competition_type).' '.$competition->name.'</h3>',
			'headingOptions' => [
				'template' => '{title}'
			],
	    ],
        'attributes' => [
            'id',
            [
                'attribute'=>'competition_type',
                'label'=>'Competition Type',
                'value'=> Yii::t('golf', $competition->competition_type),
            ],
            [
                'attribute'=>'parent_id',
                'label'=>'Competition',
                'value'=> $competition->parent ? $competition->parent->name : '',
            ],
            'name',
            'description',
            [
                'attribute'=>'course_id',
                'label'=>'Competition',
                'value'=> $competition->course ? $competition->course->name : '',
            ],
            'holes',
            [
                'attribute'=>'rule_id',
                'label'=>'Competition',
                'value'=> $competition->rule ? $competition->rule->name : '',
            ],
            'start_date',
            'registration_begin',
            'registration_end',
            [
                'attribute'=>'status',
                'label'=>'Status',
                'value'=> Yii::t('golf', $competition->status),
            ],
        ],
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.Yii::t('golf', 'Registration').'</h3>',
			'headingOptions' => [
				'template' => '{title}'
			],
	    ],
        'attributes' => [
            [
                'attribute'=>'status',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => $model::getLocalizedConstants('STATUS_'),
                'value'=>Yii::t('golf', $model->status),
            ],
            [
				'attribute' => 'created_at',
				'displayOnly' => true,
			],
            [
				'attribute' => 'updated_at',
				'displayOnly' => true,
			],
        ],
    ]) ?>

</div>
