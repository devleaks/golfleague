<?php

use common\models\Competition;
use common\models\search\RoundSearch;
use common\models\search\TournamentSearch;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
	        'attributes' => [
	            //'competition_type',
	            'name',
	            'description',
	            [
	                'attribute'=>'parent_id',
					'type' => DetailView::INPUT_DROPDOWN_LIST,
					'items' => $model->getParentCandidates(),
	                'label'=>Yii::t('golf','Parent'),
	                'value'=> $model->parent ? $model->parent->name.Html::a(' <span class="glyphicon glyphicon-share"></span>', ['view', 'id' => $model->parent_id]) : '',
					'visible' => $model->competition_type != Competition::TYPE_SEASON,
					'format' => 'raw',
	            ],
	            [
	                'attribute'=>'course_id',
					'type' => DetailView::INPUT_DROPDOWN_LIST,
	                'label'=> Yii::t('golf', 'Course'),
					'items' => Course::getCourseList(true),
	                'value' => $model->course ? $model->course->getFullName().Html::a(' <span class="glyphicon glyphicon-share"></span>', ['course/view', 'id' => $model->course_id]) : '' ,
					'format' => 'raw',
	            ],
	            [
	                'attribute'=>'holes',
					'type' => DetailView::INPUT_DROPDOWN_LIST,
					'items' => array(18 => '18', 9 => '9'),
	            ],
	            [
					'attribute' => 'rule_id',
					'type' => DetailView::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map([''=>'']+Rule::find()->where(['competition_type' => $model->competition_type])->asArray()->all(), 'id', 'name'),
					'value' => $model->rule->name.Html::a(' <span class="glyphicon glyphicon-share"></span>', ['rule/view', 'id' => $model->rule_id]),
					'format' => 'raw',
				],
	            [
					'attribute' => 'final_rule_id',
					'type' => DetailView::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map([''=>'']+Rule::find()->where(['competition_type' => $model->competition_type])->asArray()->all(), 'id', 'name'),
					'value' => $model->final_rule_id ? $model->finalRule->name.Html::a(' <span class="glyphicon glyphicon-share"></span>', ['rule/view', 'id' => $model->final_rule_id]) : '',
					'format' => 'raw',
				],
	            [
	                'attribute'=>'start_date',
					'format' => 'datetime',
					'type' => DetailView::INPUT_DATETIME,
					'widgetOptions' => [
						'pluginOptions' => [
		                	'format' => 'yyyy-mm-dd hh:ii:ss',
		                	'todayHighlight' => true
		            	]
					],
					'value' => $model->registration_begin ? new DateTime($model->start_date) : '',
	            ],
	            [
	                'attribute'=>'recurrence',
					'format' => 'raw',
					'type' => DetailView::INPUT_TEXT,
					'value' => /*$model->recurrence.'='.*/$recurrence,
	            ],
	            [
	                'attribute'=>'registration_begin',
					'format' => 'datetime',
					'type' => DetailView::INPUT_DATETIME,
					'widgetOptions' => [
						'pluginOptions' => [
		                	'format' => 'yyyy-mm-dd hh:ii:ss',
		                	'todayHighlight' => true
		            	]
					],
					'value' => $model->registration_begin ? new DateTime($model->registration_begin) : '',
	            ],
	            [
	                'attribute'=>'registration_end',
					'format' => 'datetime',
					'type' => DetailView::INPUT_DATETIME,
					'widgetOptions' => [
						'pluginOptions' => [
		                	'format' => 'yyyy-mm-dd hh:ii:ss',
		                	'todayHighlight' => true
		            	]
					],
					'value' => $model->registration_end ? new DateTime($model->registration_end) : '',
	            ],
	            'handicap_min',
	            'handicap_max',
	            'age_min',
	            'age_max',
	            [
					'attribute' => 'gender',
					'type' => DetailView::INPUT_DROPDOWN_LIST,
					'items' => [''=>'']+Competition::getLocalizedConstants('GENDER_')
				],
	            'max_players',
	            [
	                'attribute'=>'registration_special',
					'type' => DetailView::INPUT_DROPDOWN_LIST,
					'items' => [''=>'']+Competition::getLocalizedConstants('SPECIAL_')
	            ],
	        	'registration_time',
	            'flight_size',
				'flight_time',
	            [
					'attribute' => 'status',
					'type' => DetailView::INPUT_DROPDOWN_LIST,
					'items' => Competition::getLocalizedConstants('STATUS_')
				],
        ],
    ]) ?>

<?php
		if(in_array($model->competition_type, [Competition::TYPE_SEASON, Competition::TYPE_TOURNAMENT])) {
			switch($model->competition_type) {
			case Competition::TYPE_SEASON:
		        $searchModel = new TournamentSearch();
		        $dataProvider = $searchModel->search(['TournamentSearch'=>['parent_id' => $model->id]]);
				$type = Competition::TYPE_TOURNAMENT;
				break;
			case Competition::TYPE_TOURNAMENT:
		        $searchModel = new RoundSearch();
		        $dataProvider = $searchModel->search(['RoundSearch'=>['parent_id' => $model->id]]);
				$type = Competition::TYPE_ROUND;
				break;
			}
				//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

	        echo $this->render('_list', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
				'parent' => $model,
				'type' => $type
	        ]);
		}
?>

</div>
