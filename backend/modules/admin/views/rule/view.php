<?php

use common\models\Competition;
use common\models\Scorecard;
use common\models\Rule;

use kartik\detail\DetailView;

use yii\helpers\Html;
use yii\data\ActiveDataProvider;


/* @var $this yii\web\View */
/* @var $model app\models\Rule */
$model->rule_type = ($model->rule_type == Rule::TYPE_MATCHPLAY) ? 1 : 0;

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
						'onText'  => Yii::t('golf', Rule::TYPE_MATCHPLAY),
						'offText' => Yii::t('golf', Rule::TYPE_STROKEPLAY)
					]
				],
				'value' => $model->rule_type ? Yii::t('golf', Rule::TYPE_MATCHPLAY) : Yii::t('golf', Rule::TYPE_STROKEPLAY),
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
                'attribute'=>'destination_format',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => [''=>''] + Rule::getConstants('POINT_'),
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
			'value' => $model->handicap ? Yii::t('golf', 'Yes') : Yii::t('golf', 'No'),
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
