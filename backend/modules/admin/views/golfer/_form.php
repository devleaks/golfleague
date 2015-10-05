<?php

use common\models\Facility;
use common\models\Golfer;
use common\models\League;
use dektrium\user\models\User;

use yii2mod\selectize\Selectize;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Golfer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="golfer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'league_id')->dropDownList(ArrayHelper::map(League::find()->asArray()->all(), 'id', 'name'), ['prompt' => 'Select League']) ?>

    <?= $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'), ['prompt' => 'Select Yii User']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 40]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 40]) ?>

    <?= $form->field($model, 'homecourse')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'facility_id')->widget( Selectize::className(), [
			'items' => ['' => 'Select home course...'] + ArrayHelper::map(Facility::find()->where(['>', 'id', 0])->asArray()->all(), 'id', 'name'),
	]) ?>

    <?= $form->field($model, 'gender')->radioList(Golfer::getLocalizedConstants('GENDER_')) ?>

    <?= $form->field($model, 'hand')->radioList(Golfer::getLocalizedConstants('HAND_')) ?>

    <?= $form->field($model, 'handicap')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('golf', 'Create') : Yii::t('golf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
