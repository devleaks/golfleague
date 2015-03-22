<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Registration */

$this->title = Yii::t('golfleague', 'Update {modelClass}: ', [
    'modelClass' => 'Registration',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golfleague', 'Registrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('golfleague', 'Update');
?>
<div class="registration-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
