<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="news-post-short">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::textInput('search', null) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>