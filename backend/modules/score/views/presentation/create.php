<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Presentation $model
 */

$this->title = Yii::t('golf', 'Create {modelClass}', [
    'modelClass' => 'Presentation',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Presentations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="presentation-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
