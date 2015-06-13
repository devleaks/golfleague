<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="news-post-short">

    <h4><?= Html::encode($model->subject) ?></h4>

	<?php if($model->media && count($model->media) > 0) {
		$picture = $model->media[0];
		echo Html::img($picture->getThumbnailUrl(), ['class' => 'img-responsive pull-left', 'style' => 'padding: 4px;', 'alt'=>$picture->name, 'title'=>$picture->name]);
	} ?>

    <?= $model->truncate_to_n_words(Url::to(['news/view', 'id'=>$model->id])) ?>

	<div class="clearfix"></div>

</div>