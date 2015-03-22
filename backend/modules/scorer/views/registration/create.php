<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Registration */

$this->title = Yii::t('golfleague', 'Create {modelClass}', [
    'modelClass' => 'Registration',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('golfleague', 'Registrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
