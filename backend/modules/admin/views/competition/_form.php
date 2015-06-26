<?php

use common\models\Rule;
use common\models\Course;

use kartik\widgets\DateTimePicker;
use kartik\form\ActiveForm;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="competition-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'rule_id')->dropDownList([''=>'']+ArrayHelper::map(Rule::find()->where(['competition_type' => $model->competition_type])->asArray()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'final_rule_id')->dropDownList([''=>'']+ArrayHelper::map(Rule::find()->where(['competition_type' => $model->competition_type])->asArray()->all(), 'id', 'name')) ?>

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

    <?= $form->field($model, 'registration_special')->dropDownList([''=>'']+$model::getLocalizedConstants('SPECIAL_')) ?>

	<?php if($model->competition_type == $model::TYPE_MATCH): ?>

    <?= $form->field($model, 'course_id')->dropDownList(Course::getCourseList(true)) ?>

	<?= $form->field($model, 'holes')->dropDownList(array(18 => '18', 9 => '9')) ?>

	<div class='row'>
		<div class='col-lg-4'>
	    <?= $form->field($model, 'start_date')->widget(DateTimePicker::classname(), [
	            'pluginOptions' => [
	                'format' => 'yyyy-mm-dd hh:ii:ss',
	                'todayHighlight' => true
				]
	    ]) ?>
		<?= Html::activeHiddenInput($model, 'recurrence') ?>
		<div id="recurrence-text"></div>
		</div>

		<div class='col-lg-8'>
		<?php
			echo Alert::widget([
                'body' => 'Recurrence Does not Work',
                'options' => ['class' => 'alert-danger'],
			]);
            echo $this->render('scheduler', [
				'form'  => $form,
			]);
		?>
		</div>
	</div>

	<div class="clearfix"></div>
	<?php endif; ?>

    <?= $form->field($model, 'status')->dropDownList($model::getLocalizedConstants('STATUS_')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('igolf', 'Create') : Yii::t('igolf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>