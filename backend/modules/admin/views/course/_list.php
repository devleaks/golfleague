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
		'panel'=>[
	        'heading' => '<h4>'.$this->title.'</h4>',
			'footer' => Html::a(Yii::t('golf', 'Add Course'), ['course/add', 'facility_id' => $facility->id], ['class' => 'btn btn-primary']),
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
