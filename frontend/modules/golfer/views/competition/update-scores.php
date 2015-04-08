<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Competition;
use common\models\Registration;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'competition_type',
                'label'=>'Competition Type',
                'value'=> Yii::t('igolf', $model->competition_type),
            ],
            'name',
            'description',
            'course.name',
            'holes',
            'rules.name',
            'start_date',
            [
                'attribute'=>'status',
                'label'=>'Status',
                'value'=> Yii::t('igolf', $model->status),
            ],
        ],
    ]) ?>

<div class="competition-score">

<?php
    //TabularForm requires that dataProvider is build from Model::find()
    $query = Registration::find();
    $query->andWhere(['competition_id' => $model->id])->orderBy('points desc');
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);

	if($model->competition_type == Competition::TYPE_MATCH) {
		echo $this->render('../registration/scores-matches', [
	        'dataProvider' => $dataProvider,
			'model' => $model,
	    ]);
	} else {
		echo $this->render('../registration/scores-competitions', [
	        'dataProvider' => $dataProvider,
			'model' => $model,
	    ]);
	}
?>

<p></p>
<div>
	<?php
	 	if($model->competition_type != Competition::TYPE_MATCH) {
			echo ' '.Html::a(Yii::t('igolf', 'Update Points'), ['points', 'id' => $model->id], ['class' => 'btn btn-success']);
			echo ' '.Html::a(Yii::t('igolf', 'Close Competition'), ['close', 'id' => $model->id], ['class' => 'btn btn-warning']);
		} else {
			echo ' '.Html::a(Yii::t('igolf', 'Update Positions'), ['positions', 'id' => $model->id], ['class' => 'btn btn-success']);
			echo ' '.Html::a(Yii::t('igolf', 'Update Points'), ['points', 'id' => $model->id], ['class' => 'btn btn-success']);
			echo ' '.Html::a(Yii::t('igolf', 'Close Competition'), ['close', 'id' => $model->id], ['class' => 'btn btn-warning']);
		}
	?>

</div>


</div>
