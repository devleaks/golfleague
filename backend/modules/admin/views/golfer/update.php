<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Golfer */

$this->title = Yii::t('igolf', 'Update {modelClass}: ', [
    'modelClass' => 'Golfer',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Golfers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('igolf', 'Update');
?>
<div class="golfer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
