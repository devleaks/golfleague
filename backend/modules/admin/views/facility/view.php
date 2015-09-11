<?php

use common\models\Facility;
use common\models\Course;

use kartik\detail\DetailView;

use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Facility */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Facilities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="facility-view">

    <?= DetailView::widget([
	    'model' => $model,
	    'condensed'=>false,
	    'hover'=>true,
	    'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
	    'panel'=>[
		    'heading'=>'<h3 class="panel-title"> '.Html::encode($this->title).' </h3>',
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
                'value'=>Yii::t('golf',$model->units),
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
