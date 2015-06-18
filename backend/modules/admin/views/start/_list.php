<?php

use common\models\Golfer;

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\StartSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('igolf', 'Starts');

$buttons = '';

if($competition->course->hasTees()) {
	$buttons = '<div class="btn-group">';
	$buttons .= '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'. Yii::t('igolf', 'Quick Add') . ' <span class="caret"></span></button>';
	$buttons .= '<ul class="dropdown-menu" role="menu">';
    $buttons .= '<li>'.Html::a(Yii::t('igolf', 'First tees'), ['start/add', 'id' => $competition->id, 'm' => 'q']).'</a></li>';
	if($competition->course->hasTees(Golfer::GENDER_LADY) && $competition->course->hasTees(Golfer::GENDER_GENTLEMAN)) {
	    $buttons .= '<li>'.Html::a(Yii::t('igolf', 'Tees per Gender'), ['start/add', 'id' => $competition->id, 'm' => 'g']).'</a></li>';
	}
	$buttons .= '</ul>';
	$buttons .= '</div> ';
}


?>
<div class="start-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel'=>[
	        'heading' => '<h4>'.$this->title.'</h4>',
			'footer' => Html::a(Yii::t('igolf', 'Add Start'), ['start/add', 'id' => $competition->id], ['class' => 'btn btn-primary'])
						.' '.$buttons,
	    ],
		'export' => false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            // 'competition_id',
            [
				'attribute' => 'gender',
				'value' => function($model, $key, $index, $widget) {
					return Yii::t('igolf', $model->gender);
				}
			],
            'age_min',
            'age_max',
            'handicap_min',
            'handicap_max',
            'tees.name',
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}',
				'controller' => 'start',
			],
        ],
    ]); ?>

</div>
