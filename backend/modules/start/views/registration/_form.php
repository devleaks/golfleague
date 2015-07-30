<?php

use common\models\Competition;
use common\models\Golfer;
use common\models\Tees;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Registration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="registration-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'competition_id')->dropDownList(ArrayHelper::map(Competition::find()->where(['status' => Competition::STATUS_OPEN])->asArray()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'golfer_id')->dropDownList(ArrayHelper::map(Golfer::find()->asArray()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'status')->dropDownList($model::getLocalizedConstants('STATUS_')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('golf', 'Create') :
													 Yii::t('golf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
