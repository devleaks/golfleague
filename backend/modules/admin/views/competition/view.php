<?php

use common\models\Competition;
use common\models\Course;
use common\models\Rule;
use common\models\Season;
use common\models\Tournament;
use common\models\search\MatchSearch;
use common\models\search\StartSearch;
use common\models\search\TournamentSearch;

use kartik\detail\DetailView;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Season */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['index']];
if($bcs = $model->breadcrumbs())
	foreach($bcs as $bc)
		$this->params['breadcrumbs'][] = $bc;
array_pop($this->params['breadcrumbs']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.Yii::t('igolf', $model->competition_type).' '.$model->name.'</h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
            //'competition_type',
            'name',
            'description',
            [
                'attribute'=>'parent_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => $model->getParentCandidates(),
                'label'=>Yii::t('igolf','Parent'),
                'value'=> $model->parent ? $model->parent->name : '',
				'visible' => $model->competition_type != Competition::TYPE_SEASON,
            ],
            [
                'attribute'=>'course_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
                'label'=> Yii::t('igolf', 'Course'),
				'items' => Course::getCourseList(true),
                'value' => $model->course ? $model->course->getFullName() : '' ,
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
				'value' => $model->rule_id ? $model->rule->name : '',
			],
            [
				'attribute' => 'rule_final_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => ArrayHelper::map([''=>'']+Rule::find()->where(['competition_type' => $model->competition_type])->asArray()->all(), 'id', 'name'),
				'value' => $model->rule_final_id ? $model->ruleFinal->name : '',
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
			        $searchModel = new MatchSearch();
			        $dataProvider = $searchModel->search(['MatchSearch'=>['parent_id' => $model->id]]);
					$type = Competition::TYPE_MATCH;
					break;
			}

	        echo $this->render('_list', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
				'parent' => $model,
				'type' => $type
	        ]);
		}

        $startSearchModel = new StartSearch();
        $startDataProvider = $startSearchModel->search(['StartSearch'=>['competition_id' => $model->id]]);

        echo $this->render('../start/_list', [
            'searchModel' => $startSearchModel,
            'dataProvider' => $startDataProvider,
			'competition' => $model,
        ]);
?>

</div>
