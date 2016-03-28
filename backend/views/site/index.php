<?php
use devleaks\weather\Weather;

use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'Golf League - Administration';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome!</h1>

        <p class="lead">Welcome to Your Golf League Administration.</p>

        <p><a class="btn btn-lg btn-success" href="<?= Url::to(['/admin/competition']) ?>">Get started with Your Golf League</a></p>
    </div>

    <div class="body-content">

        <div class="row">
	
            <div class="col-lg-2 col-lg-offset-3">
                <h3>Create</h3>

				<ul style="list-style: none;padding-left:0;">
                    <li>&raquo; <a href="<?= Url::to(['/admin/competition']) ?>">Competitions</a></li>
                    <li>&raquo; <a href="<?= Url::to(['/admin/golfer']) ?>">Golfers</a></li>
                    <li>&raquo; <a href="<?= Url::to(['/admin/facility']) ?>">Golf courses</a></li>
                    <li>&raquo; <a href="<?= Url::to(['/admin/event']) ?>">Events</a></li>
                    <li>&raquo; <a href="<?= Url::to(['/admin/message']) ?>">Messages</a></li>
                </ul>

            </div>

            <div class="col-lg-2">
                <h3>Prepare</h3>

				<ul style="list-style: none;padding-left:0;">
					<li>&raquo; <a href="<?= Url::to(['/start/competition']) ?>">Competitions to prepare</a></li>
				</ul>
            </div>

            <div class="col-lg-2">
                <h3>Report</h3>

				<ul style="list-style: none;padding-left:0;">
					<li>&raquo; <a href="<?= Url::to(['/score/competition']) ?>">Score Reporting</a></li>
				</ul>
            </div>
        </div>


        <div class="row">
	
	        <div class="col-lg-6 col-lg-offset-3">
				<?php   echo '<div id="weather"></div>';
						if(isset(Yii::$app->params['FORECAST_APIKEY'])) {
							echo Weather::widget([
								'id' => 'weather',
								'pluginOptions' => [
									'celsius' => true,
									'cacheTime' => 60,
									'key' => Yii::$app->params['FORECAST_APIKEY'],
									'lat' => Yii::$app->params['FORECAST_DEFAULT_LAT'],
									'lon' => Yii::$app->params['FORECAST_DEFAULT_LON'],
								]
							]);
						} else {
							Yii::$app->session->setFlash('error', 'Weather: No API key.');
						}
				?>
	        </div>

        </div>



    </div>
</div>
