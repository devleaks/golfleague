<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\builder\TabularForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Holes');
?>
<div class="hole-index">

    <h2><?= Html::encode($this->title) ?></h2>
 

    <?php
        $form = ActiveForm::begin(['action'=> Yii::$app->homeUrl.'golfleague/admin/hole/updates']);

        echo TabularForm::widget([
            'form' => $form,
            'dataProvider' => $dataProvider,
            'serialColumn' => false,
            'actionColumn' => false,
            'checkboxColumn' => false,
            'attributes' => [
                'id' => [ 'type' => TabularForm::INPUT_RAW,
                          'value' => function ($model, $key, $index, $widget) {
                                        return '<input type="hidden" id="hole-'.($model->position - 1).'-id"  name="Hole['.($model->position - 1).'][id]" value="'.$model->id.'"/>'.$model->position;
                                     },
                        ],
                'par' => [ 'type' => TabularForm::INPUT_TEXT, ],
                'si' => [ 'type' => TabularForm::INPUT_TEXT, ],
                'length' => [ 'type' => TabularForm::INPUT_TEXT, ],
            ],
            //'showPageSummary' => true,
        ]);

        echo '<div>' . 
                Html::submitButton(Yii::t('igolf', 'Update Holes'), ['class'=>'btn btn-primary']) .
             '<div>';

        ActiveForm::end();
    ?>

</div>
