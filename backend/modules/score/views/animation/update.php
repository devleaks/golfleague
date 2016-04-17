<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Animation $model
 */

$this->title = Yii::t('golf', 'Update {modelClass}: ', [
    'modelClass' => 'Animation',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Animations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('golf', 'Update');
?>
<div class="animation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
