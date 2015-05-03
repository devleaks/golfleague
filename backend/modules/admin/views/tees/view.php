<?php

use common\models\Hole;
use common\models\Course;
use common\models\Tees;
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
            [
                'attribute'=>'holes',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => array(18 => '18', 9 => '9'),
            ],
            [
                'attribute'=>'front_back',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Tees::getLocalizedConstants('TEE_'),
				'visible' => $model->holes == 9,
            ],
        ],
    ]) ?>

<?php
    //TabularForm requires that dataProvider is build from Model::find()
    $query = Hole::find();
    $query->andWhere(['tees_id' => $model->id]);
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);

    echo $this->render('../hole/_updates', [
        'dataProvider' => $dataProvider,
		'tees' => $model,
    ]);
?>

</div>
