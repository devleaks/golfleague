<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Animation $model
 */

$this->title = Yii::t('golf', 'Create {modelClass}', [
    'modelClass' => 'Animation',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Animations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="animation-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
