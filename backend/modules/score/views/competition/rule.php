<?php
use common\models\Competition;

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.Yii::t('golf', $model->competition_type).' '.$model->name.'</h3>',
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
                'value'=> Yii::t('golf', $model->status),
            ],
        ],
    ]) ?>

	<?= $this->render('_scorecards', [
		'competition' => $model,
		'dataProvider' => new ActiveDataProvider([
			'query' => $model->getScorecards(),
		]),		
	])?>

	<?php
		if($model->status != Competition::STATUS_CLOSED) {
			echo Html::a(Yii::t('golf', 'Apply Rule'), Url::to(['apply-final', 'id' => $model->id]), ['class'=>'btn btn-primary']);
			echo ' ';
			echo Html::a(Yii::t('golf', 'Publish'), Url::to(['publish', 'id' => $model->id]), ['class'=>'btn btn-success']);
		} else if ($model->competition_type == Competition::TYPE_ROUND && $model->isMatchCompetition() /*&& !$model->parent*/) {
			echo Html::a(Yii::t('golf', 'Create Next Round'), Url::to(['add-round', 'id' => $model->id]), ['class'=>'btn btn-success']);
		}
	?>

</div>
