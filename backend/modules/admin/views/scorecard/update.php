<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Scorecard */

$this->title = Yii::t('igolf', 'Update {modelClass}: ', [
    'modelClass' => 'Scorecard',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Scorecards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('igolf', 'Update');
?>
<div class="scorecard-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
