<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="news-post-short">

    <h1><?= Html::encode($model->subject) ?></h1>

    <?= $model->truncate_to_n_words(Url::to(['view', 'id'=>$model->id])) ?>

</div>