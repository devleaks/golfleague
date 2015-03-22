<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Rule */

$this->title = Yii::t('igolf', 'Create {modelClass}', [
    'modelClass' => 'Rule',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rule-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
