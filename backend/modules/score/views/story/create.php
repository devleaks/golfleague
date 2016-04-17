<?php

use common\models\Story;

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Story $model
 */

$this->title = Yii::t('golf', 'Create {modelClass}', [
    'modelClass' => $model->story_type == Story::TYPE_STORY ? 'Story' : 'Story Page',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Stories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="story-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
