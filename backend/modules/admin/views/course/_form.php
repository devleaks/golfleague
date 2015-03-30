<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Facility;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Course */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="course-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'facility_id')->dropDownList(ArrayHelper::map(Facility::find()->asArray()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 80]) ?>

	<?= $form->field($model, 'holes')->dropDownList(array(18 => '18', 9 => '9')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('igolf', 'Create') : Yii::t('igolf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
