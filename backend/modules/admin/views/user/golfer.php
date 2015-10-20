<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use common\models\Facility;
use common\models\Golfer;
use common\models\League;
use common\models\User;

use yii2mod\selectize\Selectize;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $profile
 */


$this->title = Yii::t('user', 'Golfer Profile');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('settings/_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="panel-body">
                <?php $form = \yii\widgets\ActiveForm::begin([
                    'id' => 'profile-form',
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                        'labelOptions' => ['class' => 'col-lg-3 control-label'],
                    ],
                ]); ?>

			    <?= $form->field($model, 'name')->textInput(['maxlength' => 80]) ?>

			    <?= $form->field($model, 'homecourse')->textInput(['maxlength' => 80]) ?>

			    <?= $form->field($model, 'facility_id')->widget( Selectize::className(), [
						'items' => ['' => 'Select home course...'] + ArrayHelper::map(Facility::find()->where(['>', 'id', 0])->asArray()->all(), 'id', 'name'),
				]) ?>

			    <?= $form->field($model, 'gender')->radioList(Golfer::getLocalizedConstants('GENDER_')) ?>

			    <?= $form->field($model, 'hand')->radioList(Golfer::getLocalizedConstants('HAND_')) ?>

			    <?= $form->field($model, 'handicap')->textInput() ?>

                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-9">
                        <?= \yii\helpers\Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-success']) ?><br>
                    </div>
                </div>

                <?php \yii\widgets\ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
