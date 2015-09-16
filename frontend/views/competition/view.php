<?php

use common\models\Competition;
use common\models\Course;
use common\models\search\RoundSearch;
use common\models\Rule;
use common\models\Season;
use common\models\Tournament;
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

$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['index']];
if($bcs = $model->breadcrumbs())
	foreach($bcs as $bc)
		$this->params['breadcrumbs'][] = $bc;
array_pop($this->params['breadcrumbs']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="season-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3 class="panel-title">'.$model->getFullName().' <small>('.Yii::t('golf', $model->competition_type).')</small></h3>',
	    ],
		'buttons1' => false,
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
            'description',
            [
                'attribute'=>'parent_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => 	$model->getParentCandidates(),
                'value'=> $model->parent ? $model->parent->name : '',
				'visible' => $model->parent_id != '',
            ],
            [
                'attribute'=>'course_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
                'label'=> Yii::t('golf', 'Course'),
				'items' => ArrayHelper::map(Course::find()->asArray()->all(), 'id', 'name'),
                'value' => $model->course ? $model->course->name : '' ,
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
                'attribute'=>'start_date',
				'format' => 'date',
				'type' => DetailView::INPUT_DATE,
				'widgetOptions' => [
					'pluginOptions' => [
	                	'format' => 'yyyy-mm-dd hh:ii:ss',
	                	'todayHighlight' => true
	            	]
				],
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
