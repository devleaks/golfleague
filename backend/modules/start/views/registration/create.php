<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Registration */

$this->title = Yii::t('golf', 'Create {modelClass}', [
    'modelClass' => 'Registration',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Registrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
