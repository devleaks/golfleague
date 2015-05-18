<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Facility;

/* @var $this yii\web\View */
/* @var $model app\models\Facility */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="facility-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'units')->dropDownList(Facility::getLocalizedConstants('STATUS_')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('igolf', 'Create') : Yii::t('igolf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
