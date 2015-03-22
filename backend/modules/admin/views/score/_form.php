<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Score */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="score-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'scorecard_id')->textInput() ?>

    <?= $form->field($model, 'hole_id')->textInput() ?>

    <?= $form->field($model, 'score')->textInput() ?>

    <?= $form->field($model, 'putts')->textInput() ?>

    <?= $form->field($model, 'penalty')->textInput() ?>

    <?= $form->field($model, 'sand')->textInput() ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'drive')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'regulation')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'approach')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'putt')->textInput(['maxlength' => 20]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('igolf', 'Create') : Yii::t('igolf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
