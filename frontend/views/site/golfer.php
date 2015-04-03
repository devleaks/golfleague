<?php
use yii\helpers\Html;
use yii\helpers\Url;
use devleaks\metafizzy\Masonry;
use frontend\widgets\LatestMessages;
use frontend\widgets\Calendar;

/* @var $this yii\web\View */
$this->title = 'My Golf League';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-8">
                <h2>Competitions</h2>


                <h2>Calendar</h2>

				<?= Calendar::widget(); ?>

            </div>

			<?php echo ' '; /*Masonry::widget([
				'options' => ['id' => 'mycontent']
			])*/; ?>

            <div class="col-lg-4">
                <h2>Search</h2>

				<?= $this->render('_search') ?>


                <h2>Menu</h2>

				<?= $this->render('_golfer_menu') ?>

                <h2>News</h2>

				<?= LatestMessages::widget([
					'messages_count' => 3,
					'words' => 30
				]); ?>

            </div>

        </div>

    </div>

</div>
