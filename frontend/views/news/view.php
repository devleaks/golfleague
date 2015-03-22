<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->subject;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-post-short">

    <h1><?= Html::encode($model->subject) ?></h1>

    <?= $model->body ?>

</div>