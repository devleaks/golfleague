<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Courses');
?>
<div class="course-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel'=>[
	        'heading' => '<h4>'.$this->title.'</h4>',
			'footer' => Html::a(Yii::t('igolf', 'Add Course'), ['course/add', 'facility_id' => $facility->id], ['class' => 'btn btn-primary']),
	    ],
		'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'holes',

            [
                'class' => 'yii\grid\ActionColumn',
				'template' => '{view} {delete}',
                'controller' => 'course'
            ],
        ],
    ]); ?>

</div>
