<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\LatestMessages;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="news-post-short">

    <?= LatestMessages::widget([
			'messages_count' => 3,
			'words' => 30
	]); ?>

</div>