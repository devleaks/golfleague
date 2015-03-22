<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\builder\TabularForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golfleague', 'Scores');
?>
<div class="registration-index">

    <h2><?= Html::encode($this->title) ?></h2>
 

    <?php
        $form = ActiveForm::begin(['action'=> Yii::$app->homeUrl.'golfleague/score/registration/update-scores']);

        echo TabularForm::widget([
            'form' => $form,
            'dataProvider' => $dataProvider,
            'serialColumn' => false,
            'actionColumn' => false,
            'checkboxColumn' => false,
            'attributes' => [
                'id' => [ 'type' => TabularForm::INPUT_RAW,
						  'label' => Yii::t('golfleague', 'Name'),
                          'value' => function ($model, $key, $index, $widget) {
                                        return '<input type="hidden" name="Registration['.$index.'][id]" value="'.$model->id.'"/>'.$model->golfer->name;
                                     },
                        ],
                'score' => [ 'type' => TabularForm::INPUT_TEXT, ],
                'score_net' => [ 'type' => TabularForm::INPUT_TEXT, ],
                'position' => [ 'type' => TabularForm::INPUT_TEXT],
                'points' => [ 'type' => TabularForm::INPUT_TEXT],
            ],
            //'showPageSummary' => true,
        ]);

        echo '<div>' . 
                Html::submitButton(Yii::t('golfleague', 'Update Scores'), ['class'=>'btn btn-primary']) . ' ' .
             '<div>';

        ActiveForm::end();
    ?>


</div>
