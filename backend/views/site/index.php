<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'Golf League - Administration';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome!</h1>

        <p class="lead">Welcome to Your Golf League Administration.</p>

        <p><a class="btn btn-lg btn-success" href="<?= Url::to(['']) ?>">Get started with Your Golf League</a></p>
    </div>

    <div class="body-content">

        <div class="row">
	
            <div class="col-lg-4">
                <h2>Menu for Managers</h2>

                <ul>
                    <li><a href="<?= Url::to(['/admin/competition']) ?>">Competitions</a></li>
                    <li><a href="<?= Url::to(['/admin/golfer']) ?>">Golfers</a></li>
                    <li><a href="<?= Url::to(['/admin/facility']) ?>">Golf courses</a></li>
                    <li><a href="<?= Url::to(['/admin/event']) ?>">Calendar events</a></li>
                    <li><a href="<?= Url::to(['/admin/message']) ?>">Site messages</a></li>
                </ul>

            </div>

            <div class="col-lg-4">
                <h2>Menu for Starters</h2>

                <ul>
                    <li><a href="<?= Url::to(['/start']) ?>">Starter Home</a></li>
                </ul>
				<ul style="list-style: none;">
					<li>&raquo; <a href="<?= Url::to(['/start/competition']) ?>">Competitions</a></li>
					<li>&raquo; <a href="<?= Url::to(['/start/registration']) ?>">Registrations</a></li>
				</ul>
            </div>

            <div class="col-lg-4">
                <h2>Menus for Scorers</h2>

                <ul>
                    <li><a href="<?= Url::to(['/score']) ?>">Scorer Home</a></li>
                </ul>
				<ul style="list-style: none;">
					<li>&raquo; <a href="<?= Url::to(['/score/competition']) ?>">Competitions</a></li>
					<li>&raquo; <a href="<?= Url::to(['/score/scorecards']) ?>">Scorecards</a></li>
				</ul>
            </div>
        </div>

    </div>
</div>
