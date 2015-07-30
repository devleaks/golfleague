<?php

use common\models\Golfer;

use kartik\detail\DetailView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Start */

$this->title = $model->competition->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->competition->name, 'url' => ['competition/view', 'id' => $model->competition_id]];
$this->params['breadcrumbs'][] = Yii::t('golf', 'Starts');
?>
<div class="start-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h2>'.$this->title.'</h2>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
            [
                'attribute'=>'gender',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Golfer::getLocalizedConstants('GENDER_'),
            ],
            'age_min',
            'age_max',
            'handicap_min',
            'handicap_max',
            [
                'attribute'=>'tees_id',
				'value' => $model->tees->name,
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => ArrayHelper::map($model->competition->course->getTeesWithHoles()->all(), 'id', 'name'),
            ],
            [
                'attribute'=>'created_at',
				'options' => ['readonly' => true],
				'format' => 'datetime',
			],
            [
                'attribute'=>'updated_at',
				'options' => ['readonly' => true],
				'format' => 'datetime',
			],
        ],
    ]) ?>

</div>
