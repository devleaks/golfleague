<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'Golf League - Starter';
?>
<div class="starter-default-index">

	<h1>The Starter</h1>
	<p>
	    The role of the starter is to organise the competition before it happens.
	</p>
	<p>
	    The main tasks of the Starter are:
	</p>

	Before or during the registration period:
	<ul>
		<li>Approve registrations submitted by players,</li>
		<li>Register players to competitions,</li>
	</ul>

	After the registration period ends:
	<ul>
		<li>Make flights,</li>
		<li>Assign starting tees for each player,</li>
		<li>Mark the competition as ready to be played.</li>
	</ul>

	<hr/>

	<ul>
		<li><a href="<?= Url::to(['/starter/competition']) ?>">Competitions</a></li>
		<li><a href="<?= Url::to(['/starter/registration']) ?>">Registrations</a></li>
		<li><a href="<?= Url::to(['/starter/flight']) ?>">Flights</a></li>
	</ul>

</div>
