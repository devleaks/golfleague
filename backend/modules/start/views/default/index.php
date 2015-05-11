<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'Golf League - Starter';
?>
<div class="start-default-index">

	<h1>The Starter</h1>
	<p>
	    The role of the starter is to organise the competition before it happens.
	</p>
	<p>
	    The main tasks of the Starter are:
	</p>

	<ul>
		<li>Before or during the registration period:
			<ul>
				<li>Approve registrations submitted by players,</li>
				<li>Register players to competitions,</li>
			</ul>
		</li>

		<li>After the registration period ends:
			<ul>
				<li>Assign starting tees for each player,</li>
				<li>Make flights,</li>
				<li>Give start time,</li>
				<li>Mark the competition as ready to be played.</li>
			</ul>
		</li>
	</ul>

	<hr/>

	<ul style="list-style: none;">
		<li>&raquo; <a href="<?= Url::to(['/start/competition']) ?>">Competitions</a></li>
		<li>&raquo; <a href="<?= Url::to(['/start/registration']) ?>">Registrations</a></li>
		<li>&raquo; <a href="<?= Url::to(['/start/competition', '#' => 'start']) ?>">Flights</a></li>
	</ul>

</div>
