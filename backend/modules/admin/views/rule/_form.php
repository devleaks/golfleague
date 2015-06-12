<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Rule;

/* @var $this yii\web\View */
/* @var $model app\models\Rule */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="rule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'competition_type')->dropDownList(array(
		'TOURNAMENT' => Yii::t('igolf', 'Tournament'),
		'MATCH' => Yii::t('igolf', 'Match'),
    )) ?>

    <?= $form->field($model, 'object_type')->dropDownList(array(
        'SEASON' => Yii::t('igolf', 'Season'),
        'TOURNAMENT' => Yii::t('igolf', 'Tournament'),
        'MATCH' => Yii::t('igolf', 'Match'),
    )) ?>

    <?= $form->field($model, 'rule_type')->dropDownList(
		Rule::getConstants('TYPE_')
    ) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('igolf', 'Create') : Yii::t('igolf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
