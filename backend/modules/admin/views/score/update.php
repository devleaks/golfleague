<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Score */

$this->title = Yii::t('igolf', 'Update {modelClass}: ', [
    'modelClass' => 'Score',
]) . ' ' . $model->scorecard_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Scores'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->scorecard_id, 'url' => ['view', 'scorecard_id' => $model->scorecard_id, 'hole_id' => $model->hole_id]];
$this->params['breadcrumbs'][] = Yii::t('igolf', 'Update');
?>
<div class="score-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
