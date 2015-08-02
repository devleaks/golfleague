<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'Golf League - Scorer';
?>
<div class="start-default-index">

	<h1>The Scorer</h1>
	<p>
	    The role of the scorer is to enter match score, and adjust tournament and season scores accordingly.
	</p>
	<p>
	    The main tasks of the Scorer are:
	</p>

	<ul>
		<li>During a competition:
			<ul>
				<li>Enter score as competition progresses to update live score boards,</li>
			</ul>
		</li>

		<li>After the competition ends:
			<ul>
				<li>Enter, approve and confirm player scores,</li>
				<li>Adjust competition final results,</li>
				<li>Adjust tournament and season scores, if applicable.</li>
			</ul>
		</li>
	</ul>

	<hr/>

	<ul style="list-style: none;">
		<li>&raquo; <a href="<?= Url::to(['/score/competition']) ?>">Rounds</a></li>
		<li>&raquo; <a href="<?= Url::to(['/score/competition/tournaments']) ?>">Tournaments</a></li>
	</ul>

</div>
