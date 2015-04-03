<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="news-post-short">

	<ul>
		<li><a href="<?= Url::to(['/calendar']) ?>">Calendar</a></li>
		<li><a href="<?= Url::to(['/news']) ?>">News</a></li>
		<li><a href="<?= Url::to(['/golfer']) ?>">Your Profile</a></li>
		<li><a href="<?= Url::to(['/golfer/registration']) ?>">Registrations</a></li>

		<li>Enter scores</li>
		<li>View scores</li>
		<li>View standing tournament</li>
		<li>View standing season</li>
		<li>Enter practice scorecard</li>
		<li>View scorecards</li>
		<li>View stats</li>
	</ul>

</div>