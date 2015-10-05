<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->subject;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-post">

    <h1><?= Html::encode($model->subject) ?></h1>

	<?php if($model->media && count($model->media) > 0) {
		$picture = $model->media[0];
		echo Html::img($picture->getThumbnailUrl(), ['class' => 'img-responsive pull-left', 'style' => 'padding: 4px;', 'alt'=>$picture->name, 'title'=>$picture->name]);
	} ?>

    <?= $model->body ?>

</div>