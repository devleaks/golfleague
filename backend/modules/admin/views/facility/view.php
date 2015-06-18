<?php

use yii\data\ActiveDataProvider;
use kartik\detail\DetailView;
use common\models\Facility;
use common\models\Course;

/* @var $this yii\web\View */
/* @var $model app\models\Facility */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Facilities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="facility-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.$model->name.'</h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
            'name',
            'phone',
            'email:email',
            'website',
            [
                'attribute'=>'units',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Facility::getLocalizedConstants('UNITS_'),
                'value'=>Yii::t('igolf',$model->units),
            ],
        ],
    ]) ?>

<?php
        $dataProvider = new ActiveDataProvider([
			'query' => $model->getCourses(),
		]);
		
        echo $this->render('../course/_list', [
            'dataProvider' => $dataProvider,
			'facility' => $model,
        ]);
?>

<?=	$this->render('../media/_add', [
	'model' => $model,
])?>

</div>
