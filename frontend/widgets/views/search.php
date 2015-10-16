<?php

use common\models\Competition;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="search-result">

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
		'itemView' => '_search',
		'viewParams'=> ['detail_url' => $detail_urls],
		'summary' => false,
    ]); ?>

</div>
