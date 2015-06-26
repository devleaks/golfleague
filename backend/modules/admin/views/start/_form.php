<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Competition;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Start */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="start-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'gender')->radioList(Competition::getLocalizedConstants('GENDER_')) ?>

    <?= $form->field($model, 'age_min')->textInput() ?>

    <?= $form->field($model, 'age_max')->textInput() ?>

    <?= $form->field($model, 'handicap_min')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'handicap_max')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'tees_id')->dropDownList(ArrayHelper::map($model->competition->course->getTeesWithHoles()->asArray()->all(), 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
