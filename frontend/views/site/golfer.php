<?php

use frontend\widgets\LatestMessages;
use frontend\widgets\Calendar;

use kartik\grid\GridView;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;


/* @var $this yii\web\View */
$this->title = 'Yii Golf League';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-8">
                <h2><?= Yii::t('golf', 'Calendar of Competitions') ?></h2>

				<?= Tabs::widget([
						'items' => [
							[
					            'label' => Yii::t('golf', 'Calendar'),
					            'content' => Calendar::widget(),
					            'active' => true
					        ],
					        [
					            'label' => Yii::t('golf', 'List'),
					            'content' => GridView::widget([
							        'dataProvider' => $competitions,
									'panel'=>[
								        'heading' => '<h4>'.$this->title.'</h4>',
								    ],
									'export' => false,
							        'columns' => [
							            'name',
							            'description',
							            'registration_begin',
							            'registration_end',
							            [
							                'class' => 'kartik\grid\ActionColumn',
											'template' => '{view}',
											'noWrap' => true,
							            ],
							        ],
							    ]),
					        ],				
						],
						'options' => ['style' => 'margin-bottom: 20px;']
					])
				?>

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
