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
                    <li><a href="<?= Url::to(['/starter']) ?>">Starter Home</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h2>Menus for Scorers</h2>

                <ul>
                    <li><a href="<?= Url::to(['/scorer']) ?>">Scorer Home</a></li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <h2>Additional Menu for Super-Administrators</h2>

                <ul>
                    <li><a href="<?= Url::to(['/']) ?>">Manage Golf League Application</a></li>
                    <li><a href="<?= Url::to(['/admin']) ?>">Site admin</a></li>
                </ul>

            </div>
            <div class="col-lg-4">
                <h2>Menu for Golfers</h2>

                <ul>
                    <li><a href="<?= Url::to(['/../golfleague']) ?>">Golfer Home</a></li>
                </ul>

            </div>
            <div class="col-lg-4">
                <h2>Menus for Visitors</h2>

                <ul>
                    <li><a href="<?= Url::to(['/competition']) ?>">«All» competitions</a></li>
					<ul>
                    <li>Upcoming competitions</li>
                    <li>Recent competitions</li>
                    <li>Archives (past competitions)</li>
					</ul>
                    <li>Calendar</li>
                    <li>The Wire</li>
                    <li>Leaderboards</li>
                    <li>Standings</li>
                </ul>

            </div>
        </div>

    </div>
</div>
