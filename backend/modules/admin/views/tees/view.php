<?php

use common\models\Hole;
use common\models\Course;
use common\models\Tees;
use common\models\Competition;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tees */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Facilities'), 'url' => ['facility/index']];
$this->params['breadcrumbs'][] = ['label' => $model->course->facility->name, 'url' => ['facility/view', 'id' => $model->course->facility_id]];
$this->params['breadcrumbs'][] = ['label' => $model->course->name, 'url' => ['course/view', 'id' => $model->course->id]];
$this->params['breadcrumbs'][] = $this->title . ' (' . Yii::t('igolf', ucfirst($model->color)) . ')';
?>
<div class="tees-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h2>'.$model->name.'</h2>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
//            'id',
            [
                'attribute'=>'course_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
                'label'=> Yii::t('igolf', 'Course'),
				'items' => ArrayHelper::map(Course::find()->asArray()->all(), 'id', 'name'),
                'value' => $model->course->name,
            ],
            'name',
            [
                'attribute'=>'color',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Yii::$app->params['tees_colors'],
                'value'=>Yii::t('igolf', ucfirst($model->color)),
            ],
            'par',
            [
                'attribute'=>'holes',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => array(18 => '18', 9 => '9'),
            ],
            [
				'attribute' => 'gender',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Tees::getLocalizedConstants('GENDER_')
			],
	        [
				'attribute' => 'category',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Tees::getLocalizedConstants('CATEGORY_')
			],
            [
                'attribute'=>'front_back',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Tees::getLocalizedConstants('TEE_'),
				'visible' => $model->holes == 9,
            ],
			'course_rating',
			'slope_rating',
        ],
    ]) ?>

<?php
    //TabularForm requires that dataProvider is build from Model::find()
    $dataProvider = new ActiveDataProvider([
        'query' => $model->getHoles(),
    ]);

    echo $this->render('../hole/_updates', [
        'dataProvider' => $dataProvider,
		'tees' => $model,
    ]);
?>

</div>
