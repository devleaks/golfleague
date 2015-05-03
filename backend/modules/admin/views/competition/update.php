<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = Yii::t('igolf', 'Update {modelClass}: ', [
    'modelClass' => 'Competition',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('igolf', 'Update');
?>
<div class="competition-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
