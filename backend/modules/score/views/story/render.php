<?php

use common\models\Animation;
use common\models\Presentation;
use common\models\Story;

use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use vova07\imperavi\Widget as Redactor;

/**
 * @var yii\web\View $this
 * @var common\models\Story $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Stories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="story-view">

<h1><?= Html::encode($model->title) ?></h1>

<?= $model->body ?>

</div>
