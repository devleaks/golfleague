<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Model-with-picture */

?>
<div class="media-update">

<?php
    foreach($model->getMedia()->each() as $picture) {
		echo Html::img($picture->getThumbnailUrl());
		echo Html::a('<i glyphicon glyphicon-remove></i>', Url::to(['delete-picture', 'id' => $picture->id]), ['class' => 'btn btn-warning']);
		echo '&nbsp;';
    }
?>

</div>