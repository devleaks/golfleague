<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Marker $model
 */

$this->title = Yii::t('golf', 'Update {modelClass}: ', [
    'modelClass' => 'Marker',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Markers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('golf', 'Update');
?>
<div class="marker-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
