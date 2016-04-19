<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Story $model
 */
?>
<div class="story-page">

<h3><?= Html::encode($model->title) ?></h3>

<?= $model->body ?>

<hr />
</div>
