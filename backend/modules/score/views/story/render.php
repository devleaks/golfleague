<?php

use common\models\Story;

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Story $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Stories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="story">

<h1><?= Html::encode($model->title) ?></h1>

<?= $model->body ?>

<?php
	if($model->story_type == Story::TYPE_STORY) {
		foreach($model->getStories()->orderBy('position')->each() as $story)
	        echo $this->render('_render', [
	            'model' => $story,
	        ]);
	}
?>


</div>
