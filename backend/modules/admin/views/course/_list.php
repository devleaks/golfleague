<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Courses');
?>
<div class="course-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'before'=>Html::a(Yii::t('golf', 'Add Course'), ['course/add', 'facility_id' => $facility->id], ['class' => 'btn btn-success']),                                                                                                                                                          	
//			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
		'export' => false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'name',
            'holes',

            [
                'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}',
                'controller' => 'course'
            ],
        ],
    ]); ?>

</div>
