<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Golf League';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome!</h1>

        <p class="lead">Welcome to Your Golf League Account.</p>

        <p><a class="btn btn-lg btn-success" href="<?= Url::to(['/']) ?>">Get started with Golf League</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2>Menu for Golfers</h2>

                <ul>
                    <li><a href="<?= Url::to(['/calendar']) ?>">Calendar</a></li>
                    <li><a href="<?= Url::to(['/golfer']) ?>">Your Profile</a></li>
                    <li><a href="<?= Url::to(['/golfer/competition']) ?>">«All» competitions</a></li>
                    <li><a href="<?= Url::to(['/golfer/registration']) ?>">Register/deregister(/forfeit) to match</a></li>
                    <li><a href="<?= Url::to(['/golfer/registration/tournaments']) ?>">Register/deregister to tournaments</a></li>
                    <li><a href="<?= Url::to(['/golfer/registration/seasons']) ?>">Register/deregister to seasons</a></li>

                    <li>Enter scores</li>
                    <li>View scores</li>
                    <li>View standing tournament</li>
                    <li>View standing season</li>
                    <li>Enter practice scorecard</li>
                    <li>View scorecards</li>
                    <li>View stats</li>
                </ul>

            </div>
            <div class="col-lg-6">
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
