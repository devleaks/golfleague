<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="latest-messages-portlet portlet">

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
		'itemView' => '_post-short',
		'summary' => false,
    ]); ?>

</div>
