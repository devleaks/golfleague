<?php

use yii\helpers\Html;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model common\models\Practice */

$this->title = $model->course->name.' » '.Yii::$app->formatter->asDateTime($model->start_time);
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Practice Rounds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('igolf', 'Update');
?>
<div class="practice-update">

    <?= $this->render('_updpractice', [
        'model' => $model,
    ]) ?>

    <?= $this->render('_updscore', [
		'model' => $model,
        'dataProvider' => new ActiveDataProvider([
			'query' => $model->getScorecard(true)->getScores()
		]),
    ]) ?>

</div>
