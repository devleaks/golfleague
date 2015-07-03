<?php

use common\models\Competition;
use common\models\Scorecard;
use common\models\Rule;

use kartik\widgets\SwitchInput;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Rule */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="rule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'competition_type')->dropDownList(Competition::getLocalizedConstants('TYPE_'))
			->hint(Yii::t('igolf', 'Rule applies to this type of competition')) ?>

    <?= $form->field($model, 'source_type')->dropDownList(Scorecard::getConstants('SCORE_'))
			->hint(Yii::t('igolf', 'Source column for score')) ?>

    <?= $form->field($model, 'source_direction')->dropDownList(Scorecard::getConstants('DIRECTION_'))
			->hint(Yii::t('igolf', 'Source column for score ordering. ASC is smallest wins to largest looses. DESC is opposite.')) ?>

    <?= $form->field($model, 'destination_type')->dropDownList(Scorecard::getConstants('SCORE_'))
			->hint(Yii::t('igolf', 'Destination column for result.')) ?>

	<?=	$form->field($model, 'handicap')->widget(SwitchInput::className(), [
		'pluginOptions' => [
			'onText'  => Yii::t('igolf', '   Use Handicap    '),
			'offText' => Yii::t('igolf', 'Do Not Use Handicap')
		]
	]) ?>
			
    <?= $form->field($model, 'team')->dropDownList(Rule::getTeamList())
			->hint(Yii::t('igolf', 'Camp size')) ?>

    <?= $form->field($model, 'classname')->dropDownList(Rule::getList())
			->hint(Yii::t('igolf', 'Rule to apply')) ?>

	<?= $form->field($model, 'parameters')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('igolf', 'Create') : Yii::t('igolf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
