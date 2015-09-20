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
		<li><a href="<?= Url::to(['/golfer']) ?>">Your Golf Profile</a></li>
		<li><a href="<?= Url::to(['/golfer/practice']) ?>">Practice rounds</a></li>
		<li><a href="<?= Url::to(['/golfer/registration']) ?>">Registrations</a></li>

		<li>Enter scores</li>
		<li>Competitions</li>
		<li>Statistics</li>
	</ul>

</div>