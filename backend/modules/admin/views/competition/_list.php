<?php

use common\models\Competition;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TournamentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', $parent->childType());
?>
<div class="tournament-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel'=>[
	        'heading' => '<h4>'.$this->title.'</h4>',
			'footer' => Html::a(Yii::t('igolf', 'Add {0}',$parent->childType()), ['add', 'parent_id' => $parent->id], ['class' => 'btn btn-primary']),
	    ],
		'export' => false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            //'competition_type',
            'name',
            'description',
            //'parent_id',
            // 'course_id',
            // 'holes',
            // 'rule_id',
            // 'start_date',
            'registration_begin',
            'registration_end',
            // 'handicap_min',
            // 'handicap_max',
            // 'age_min',
            // 'age_max',
            // 'gender',
            [
				'attribute' => 'status',
                'label' => Yii::t('igolf', 'Status'),
				'filter' => Competition::getLocalizedConstants('STATUS_'),
                'value'=> function($model, $key, $index, $widget) {
                    return Yii::t('igolf', $model->status);
				},
            ],
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}',
				'noWrap' => true,
            ],
        ],
    ]); ?>

</div>
