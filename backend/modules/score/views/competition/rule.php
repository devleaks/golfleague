<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.Yii::t('igolf', $model->competition_type).' '.$model->name.'</h3>',
			'headingOptions' => [
				'template' => '{title}'
			],
			'footer' => false,
	    ],
        'attributes' => [
            'description',
            [
                'attribute'=>'parent_id',
                'label'=>'Competition',
                'value'=> $model->parent ? $model->parent->name : '',
            ],
            [
                'attribute'=>'course_id',
                'value'=> $model->course ? $model->course->name : '',
            ],
            'holes',
            [
                'attribute'=>'rule_id',
                'value'=> $model->rule ? $model->rule->name : '',
            ],
            'start_date',
            [
                'attribute'=>'status',
                'label'=>'Status',
                'value'=> Yii::t('igolf', $model->status),
            ],
        ],
    ]) ?>

	<?= $this->render('_scorecards', [
		'competition' => $model,
		'dataProvider' => new ActiveDataProvider([
			'query' => $model->getScorecards(),
		]),		
	])?>

	<?= Html::a(Yii::t('igolf', 'Apply Rule'), Url::to(['apply-final', 'id' => $model->id]), ['class'=>'btn btn-primary']).
		' '.
		Html::a(Yii::t('igolf', 'Publish'), Url::to(['publish', 'id' => $model->id]), ['class'=>'btn btn-success'])
	?>

</div>
