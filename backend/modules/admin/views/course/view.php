<?php

use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use kartik\detail\DetailView;
use common\models\Facility;
use common\models\search\TeesSearch;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Course */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Facilities'), 'url' => ['facility/index']];
$this->params['breadcrumbs'][] = ['label' => $model->facility->name, 'url' => ['facility/view', 'id' => $model->facility_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3 class="panel-title">'.Html::encode($this->title).' </h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
            [
                'attribute'=>'facility_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => ArrayHelper::map(Facility::find()->asArray()->all(), 'id', 'name'),
                'label'=>'Facility',
                'value'=>$model->facility->name,
            ],
            'name',
            [
                'attribute'=>'holes',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => array(18 => '18', 9 => '9'),
            ],
        ],
    ]) ?>


<?php
		$dataProvider = new ActiveDataProvider([
			'query' => $model->getTees(),
		]);

        echo $this->render('../tees/_list', [
            'dataProvider' => $dataProvider,
			'course' => $model,
        ]);
?>

<?=	$this->render('../media/_add', [
	'model' => $model,
])?>

</div>
