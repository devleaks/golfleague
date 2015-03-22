<?php

use common\models\Competition;
use common\models\Golfer;
use common\models\Tees;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Registration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="registration-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'competition_id')->dropDownList(ArrayHelper::map(Competition::find()->where(['status' => Competition::STATUS_OPEN])->asArray()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'golfer_id')->dropDownList(ArrayHelper::map(Golfer::find()->asArray()->all(), 'id', 'name')) ?>

    <?php
 		if($model->competition->course_id)
			echo $form->field($model, 'tees_id')->dropDownList(ArrayHelper::map(Tees::find()->where(['course_id' => $model->competition->course_id])->asArray()->all(), 'id', 'name'));
	?>

    <?= $form->field($model, 'status')->dropDownList($model::getStatuses()) ?>

    <?= $form->field($model, 'score')->textInput() ?>

    <?= $form->field($model, 'score_net')->textInput() ?>

    <?= $form->field($model, 'points')->textInput() ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('golfleague', 'Create') : Yii::t('golfleague', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
