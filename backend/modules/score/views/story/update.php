<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Story $model
 */

$this->title = Yii::t('golf', 'Update {modelClass}: ', [
    'modelClass' => 'Story',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Stories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('golf', 'Update');
?>
<div class="story-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
