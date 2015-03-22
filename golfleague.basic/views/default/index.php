<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Yii Golf League Application';
?>
<div class="admin-default-index">

    <div class="jumbotron">
        <h1>Yii Golf League</h1>

        <p class="lead">Welcome to Your Golf League Application.</p>

        <p><a class="btn btn-lg btn-success" href="<?= Yii::$app->homeUrl ?>golfleague/admin/facility/">Get started with Golf League Application</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Menu for Managers</h2>

                <ul>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/admin/">Admin Home</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/admin/season/">«All» competitions</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/admin/facility/">Manage golf courses</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/admin/golfer/">Manage golfers</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/admin/event/">Calendar Events</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/admin/message/">Messages</a></li>
                </ul>

            </div>
            <div class="col-lg-4">
                <h2>Menu for Starters</h2>

                <ul>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/starter/">Starter Home</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/starter/competition/">«All» competitions</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/starter/registration/">Manage registrations</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/starter/flight/">Manage flights</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h2>Menus for Scorers</h2>

                <ul>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/score/">Scorer Home</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/score/competition/">«All» competitions</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/score/registration/">Manage scores</a></li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <h2>Menu for Golfers</h2>

                <ul>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/golfer/">Golfer Home</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/golfer/competition">«All» competitions</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/golfer/registration/">Register/deregister(/forfeit) to match</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/golfer/registration/tournaments">Register/deregister to tournaments</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/golfer/registration/seasons">Register/deregister to seasons</a></li>

                    <li>Enter scores</li>
                    <li>View scores</li>
                    <li>View standing tournament</li>
                    <li>View standing season</li>
                    <li>Enter practice scorecard</li>
                    <li>View scorecards</li>
                    <li>View stats</li>
                </ul>

            </div>
            <div class="col-lg-4">
                <h2>Additional Menu for Super-Administrators</h2>

                <ul>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/">Manage Golf League Application</a></li>
                    <li><a href="<?= Yii::$app->homeUrl ?>admin/">Site admin</a></li>
                </ul>

            </div>
            <div class="col-lg-4">
                <h2>Menus for Visitors</h2>

                <ul>
                    <li><a href="<?= Yii::$app->homeUrl ?>golfleague/competition">«All» competitions</a></li>
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
</div>