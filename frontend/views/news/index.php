<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use frontend\widgets\LatestMessages;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'News');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <?= LatestMessages::widget([
			'message_count' => 3
	]); ?>

</div>
