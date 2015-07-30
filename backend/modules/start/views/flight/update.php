<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Flight */

$this->title = Yii::t('golf', 'Update {modelClass}: ', [
    'modelClass' => 'Flight',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Flights'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('golf', 'Update');
?>
<div class="flight-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
