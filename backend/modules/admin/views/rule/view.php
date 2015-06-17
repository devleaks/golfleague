<?php

use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use kartik\detail\DetailView;
use common\models\Competition;
use common\models\Scorecard;
use common\models\Rule;

/* @var $this yii\web\View */
/* @var $model app\models\Rule */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competition Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rule-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.$model->name.'</h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
//            'id',
            'name',
            'description',
            'note',
            [
                'attribute'=>'competition_type',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Competition::getConstants('TYPE_'),
            ],
            [
                'attribute'=>'source_type',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Scorecard::getConstants('SCORE_'),
            ],
            [
                'attribute'=>'source_direction',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Scorecard::getConstants('DIRECTION_'),
            ],
            [
                'attribute'=>'destination_type',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => [''=>''] + Scorecard::getConstants('SCORE_'),
            ],
            [
                'attribute'=>'team',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Rule::getTeamList(),
            ],
            [
                'attribute'=>'classname',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Rule::getList(),
            ],
            'parameters',
        ],
    ]) ?>



<?php
		$dataProvider = new ActiveDataProvider([
			'query' => $model->getPoints(),
		]);

        echo $this->render('../point/_updates', [
            'dataProvider' => $dataProvider,
			'rule' => $model
        ]);
?>

</div>
