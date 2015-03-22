<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Hole */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hole-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tees_id')->textInput() ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <?= $form->field($model, 'par')->textInput() ?>

    <?= $form->field($model, 'si')->textInput() ?>

    <?= $form->field($model, 'length')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('igolf', 'Create') : Yii::t('igolf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
