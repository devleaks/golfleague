<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model Model to display */
?>
<div class="search-result-detail">

    <h4><?= Html::a($model['title'], [$detail_url[$model['source']], 'id' => $model['id']]) ?> <small><?= $model['source'].'/'.$model['type'] ?></small></h4>

    <?= $model['text'] ?>

	<div class="clearfix"></div>

</div>