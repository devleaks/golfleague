<?php

use frontend\widgets\Calendar;

use kartik\grid\GridView;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Calendar');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-index">

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