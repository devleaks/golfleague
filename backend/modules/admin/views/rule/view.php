<?php

use common\models\Competition;
use common\models\Scorecard;
use common\models\Rule;

use kartik\detail\DetailView;

use yii\helpers\Html;
use yii\data\ActiveDataProvider;


/* @var $this yii\web\View */
/* @var $model app\models\Rule */
$model->rule_type = ($model->rule_type == Rule::TYPE_MATCH) ? 1 : 0;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competition Rules'), 'url' => ['index']];
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
                'attribute'=>'rule_type',
				'type' => DetailView::INPUT_SWITCH,
				'widgetOptions' => [
				    'pluginOptions' => [
						'onText'  => Yii::t('golf', Rule::TYPE_MATCH),
						'offText' => Yii::t('golf', Rule::TYPE_STROKE)
					]
				],
				'value' => $model->rule_type ? Yii::t('golf', Rule::TYPE_MATCH) : Yii::t('golf', Rule::TYPE_STROKE),
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
				'attribute'=>'handicap',
				'type' => DetailView::INPUT_SWITCH,
				'widgetOptions' => [
				    'pluginOptions' => [
						'onText'  => Yii::t('golf', '   Use Handicap    '),
						'offText' => Yii::t('golf', 'Do Not Use Handicap')
					]
				],
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
