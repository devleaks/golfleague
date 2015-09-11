<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\League $model
 */

$this->title = Yii::t('golf', 'Update {modelClass}: ', [
    'modelClass' => 'League',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Leagues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('golf', 'Update');
?>
<div class="league-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
