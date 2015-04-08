<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Registration */

$this->title = $model->golfer->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Registrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->competition->name, 'url' => ['competition', 'id' => $model->competition->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.$this->title.'</h3>',
	    ],
        'attributes' => [
            [
                'attribute'=>'competition_id',
                'value'=>$model->competition->name,
            ],
            [
                'attribute'=>'competition_id',
                'value'=>$model->competition->competition_type,
            ],
            [
                'attribute'=>'status',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => $model::getLocalizedConstants('STATUS_'),
                'value'=>Yii::t('igolf', $model->status),
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
