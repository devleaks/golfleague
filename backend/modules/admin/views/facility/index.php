<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FacilitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Golf Courses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="facility-index">

    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('golf', 'Add {modelClass}', [
                'modelClass' => 'Facility',
            ]), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute'=>'name',
                'label'=>'Facility',
                'value'=> function($model, $key, $index, $widget) {
					return $model->name . ($model->website ? ' ' . Html::a('<span class="glyphicon glyphicon-link"></span>', $model->website, ['target' => '_blank']) : '');
				},
				'format' => 'raw'
            ],
            'phone',
            'email:email',

            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}'
			],
        ],
    ]); ?>

</div>
