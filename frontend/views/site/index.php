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

        <p><a class="btn btn-lg btn-success" href="<?= Url::to(['/user/login']) ?>">Get started with Your Golf League</a></p>

		<img src="images/welcome.png" class="center" />

    </div>

    <div class="body-content">
	
        <div class="row">
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
