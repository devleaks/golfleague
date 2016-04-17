<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Presentation $model
 */

$this->title = Yii::t('golf', 'Update {modelClass}: ', [
    'modelClass' => 'Presentation',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Presentations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('golf', 'Update');
?>
<div class="presentation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
