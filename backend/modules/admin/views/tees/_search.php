<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TeesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tees-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'course_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'color') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('igolf', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('igolf', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
