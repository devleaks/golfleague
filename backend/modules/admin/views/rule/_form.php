<?php

use common\models\Competition;
use common\models\Scorecard;
use common\models\Rule;
use common\models\League;

use kartik\widgets\SwitchInput;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Rule */
/* @var $form yii\widgets\ActiveForm */

if(! $model->rule_type) $model->rule_type = 0;
?>

<div class="rule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 80]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'league_id')->dropDownList([''=>''] + ArrayHelper::map(League::find()->asArray()->all(), 'id', 'name'))
			->hint(Yii::t('golf', 'Leave blank if rule applies to all leagues')) ?>

    <?= $form->field($model, 'competition_type')->dropDownList(Competition::getLocalizedConstants('TYPE_'))
			->hint(Yii::t('golf', 'Rule applies to this type of competition')) ?>

	<?=	$form->field($model, 'rule_type')->widget(SwitchInput::className(), [
		'pluginOptions' => [
			'onText'  => Yii::t('golf', Rule::TYPE_MATCHPLAY),
			'offText' => Yii::t('golf', Rule::TYPE_STROKEPLAY)
		]
	])->hint(Yii::t('golf', 'Type of competition: Strokeplay or Matchplay')) ?>
				
    <?= $form->field($model, 'source_type')->dropDownList(Scorecard::getConstants('SCORE_'))
			->hint(Yii::t('golf', 'Source column for score')) ?>

    <?= $form->field($model, 'source_direction')->dropDownList(Scorecard::getConstants('DIRECTION_'))
			->hint(Yii::t('golf', 'Source column for score ordering. ASC is smallest wins to largest looses. DESC is opposite.')) ?>

    <?= $form->field($model, 'destination_type')->dropDownList(Scorecard::getConstants('SCORE_'))
			->hint(Yii::t('golf', 'Destination column for result.')) ?>

	<?=	$form->field($model, 'handicap')->widget(SwitchInput::className(), [
		'pluginOptions' => [
			'onText'  => Yii::t('golf', '   Use Handicap    '),
			'offText' => Yii::t('golf', 'Do Not Use Handicap')
		]
	]) ?>
			
    <?= $form->field($model, 'team_size')->dropDownList(Rule::getTeamList())
			->hint(Yii::t('golf', 'Camp size')) ?>

    <?= $form->field($model, 'classname')->dropDownList(Rule::getList())
			->hint(Yii::t('golf', 'Rule to apply')) ?>

	<?= $form->field($model, 'parameters')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('golf', 'Create') : Yii::t('golf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
