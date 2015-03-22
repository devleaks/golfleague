<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use common\models\Course;
use common\models\CourseSearch;

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
				'items' => [
					'metric' => Yii::t('igolf', 'Metric'),
					'imperial' => Yii::t('igolf', 'Imperial')
				],
                'label'=>Yii::t('igolf','Units'),
                'value'=>Yii::t('igolf',$model->units),
            ],
        ],
    ]) ?>

<?php
        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->search(['CourseSearch'=>['facility_id' => $model->id]]);
//      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //$courseController = new courseController('course', new AdminModule('admin', new GolfLeagueModule('golfleague')) );
        echo $this->render('../course/_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'facility' => $model,
        ]);
?>

</div>
