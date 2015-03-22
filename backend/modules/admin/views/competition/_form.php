<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;
use common\models\Rule;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="competition-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'rule_id')->dropDownList([''=>'']+ArrayHelper::map(Rule::find()->where(['rule_type' => $model->competition_type])->asArray()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'registration_begin')->widget(DateTimePicker::classname(), [
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii:ss',
                'todayHighlight' => true
            ]
        ]) ?>

    <?= $form->field($model, 'registration_end')->widget(DateTimePicker::classname(), [
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii:ss',
                'todayHighlight' => true
            ]
        ]) ?>

    <?= $form->field($model, 'handicap_min')->textInput() ?>

    <?= $form->field($model, 'handicap_max')->textInput() ?>

    <?= $form->field($model, 'age_min')->textInput() ?>

    <?= $form->field($model, 'age_max')->textInput() ?>

    <?= $form->field($model, 'gender')->radioList($model::getLocalizedConstants('GENDER_')) ?>

    <?= $form->field($model, 'special')->dropDownList([''=>'']+$model::getLocalizedConstants('SPECIAL_')) ?>

    <?= $form->field($model, 'status')->dropDownList($model::getLocalizedConstants('STATUS_')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('giveaway', 'Create') : Yii::t('giveaway', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
