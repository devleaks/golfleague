<?php

use common\models\Competition;
use common\models\Course;
use common\models\Rule;
use common\models\Season;
use common\models\Tournament;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use yii\data\ActiveDataProvider;
use common\models\Registration;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

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
				'items' => 	$model->parentType() == Competition::TYPE_SEASON ?
								ArrayHelper::map(Season::find()->asArray()->all(), 'id', 'name')
								:
								(	$model->parentType() == Competition::TYPE_TOURNAMENT ?
										ArrayHelper::map(Tournament::find()->asArray()->all(), 'id', 'name')
										:
										[]
								),
                'label'=>Yii::t('igolf','Parent'),
                'value'=> $model->parent ? $model->parent->name : '',
				'visible' => $model->competition_type != Competition::TYPE_SEASON,
            ],
            [
                'attribute'=>'course_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
                'label'=> Yii::t('igolf', 'Course'),
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
				'items' => ArrayHelper::map([''=>'']+Rule::find()->where(['rule_type' => $model->competition_type])->asArray()->all(), 'id', 'name'),
				'value' => $model->rule_id ? $model->rule->name : '',
			],
            [
                'attribute'=>'start_date',
				'format' => 'date',
				'type' => DetailView::INPUT_DATE,
				'widgetOptions' => [
					'pluginOptions' => [
	                	'format' => 'yyyy-mm-dd H:i:s',
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
	                	'format' => 'yyyy-mm-dd H:i:s',
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
	                	'format' => 'yyyy-mm-dd H:i:s',
	                	'todayHighlight' => true
	            	]
				],
				'value' => $model->registration_end ? new DateTime($model->registration_end) : '',
            ],
            'cba',
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


	<?= $this->render('_registrations', [
		'competition' => $model,
		'dataProvider' => new ActiveDataProvider([
			'query' => $model->getRegistrations()->andWhere(['status' => Registration::getTerminatedStatuses()]),
		]),		
	])?>

</div>
