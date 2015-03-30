<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use common\models\Facility;
use common\models\search\TeesSearch;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Course */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Facilities'), 'url' => ['facility/index']];
$this->params['breadcrumbs'][] = ['label' => $model->facility->name, 'url' => ['facility/view', 'id' => $model->facility_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.$model->name.'</h3>',
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
            'holes',
        ],
    ]) ?>


<?php
        $searchModel = new TeesSearch();
        $dataProvider = $searchModel->search(['TeesSearch'=>['course_id' => $model->id]]);

        echo $this->render('../tees/_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'course' => $model,
        ]);
?>

<?=	$this->render('../media/_add', [
	'model' => $model,
])?>

</div>
