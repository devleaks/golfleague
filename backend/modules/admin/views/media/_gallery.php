<?php

use dosamigos\gallery\Gallery;

/* @var $this yii\web\View */
/* @var $model app\models\Model-with-picture */

?>
<div class="media-gallery">
<?php
    $pics = array();
    foreach($model->media as $picture) {
        $pics[] = [
            'url' => $picture->getFileUrl(),
            'src' => $picture->getThumbnailUrl(),
            'options' => array('title' => $picture->name)
        ];
    }
    echo Gallery::widget(['items' => $pics]);
?>
</div>