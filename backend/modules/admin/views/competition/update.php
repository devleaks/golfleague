<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = Yii::t('giveaway', 'Update {modelClass}: ', [
    'modelClass' => 'Competition',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('giveaway', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('giveaway', 'Update');
?>
<div class="competition-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
