<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Round;

/* @var $this yii\web\View */
/* @var $model common\models\Flight */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="flight-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'competition_id')->dropDownList(ArrayHelper::map(Round::find()/*->where(['status' => Round::STATUS_OPEN])*/->asArray()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => 80]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('golf', 'Create') : Yii::t('golf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
