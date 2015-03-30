<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dektrium\user\models\User;
use common\models\Facility;
use yii\helpers\ArrayHelper;
use yii2mod\selectize\Selectize;

/* @var $this yii\web\View */
/* @var $model common\models\Golfer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="golfer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'), ['prompt' => 'Select Yii User']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 40]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 40]) ?>

    <?= $form->field($model, 'homecourse')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'facility_id')->widget( Selectize::className(), [
			'items' => ['' => 'Select home course...'] + ArrayHelper::map(Facility::find()->where(['>', 'id', 0])->asArray()->all(), 'id', 'name'),
	]) ?>

    <?= $form->field($model, 'gender')->radioList(['GENTLEMAN' => Yii::t('igolf', 'GENTLEMAN'), 'LADY' => Yii::t('igolf', 'LADY')]) ?>

    <?= $form->field($model, 'hand')->radioList(['left' => Yii::t('igolf', 'left'), 'right' => Yii::t('igolf', 'right')]) ?>

    <?= $form->field($model, 'handicap')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('igolf', 'Create') : Yii::t('igolf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
