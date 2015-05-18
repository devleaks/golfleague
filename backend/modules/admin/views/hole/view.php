<?php

use common\models\Hole;
use common\models\Course;
use common\models\Tees;
use common\models\Competition;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tees */


/** @TODO
	Hole pictures are valid for all holes on same course!
	
 */

$this->title = $model->position;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Facilities'), 'url' => ['facility/index']];
$this->params['breadcrumbs'][] = ['label' => $model->tees->course->facility->name, 'url' => ['facility/view', 'id' => $model->tees->course->facility_id]];
$this->params['breadcrumbs'][] = ['label' => $model->tees->course->name, 'url' => ['course/view', 'id' => $model->tees->course->id]];
$this->params['breadcrumbs'][] = ['label' => $model->tees->name. ' (' . Yii::t('igolf', ucfirst($model->tees->color)) . ')', 'url' => ['tees/view', 'id' => $model->tees->id]];
$this->params['breadcrumbs'][] = $this->title ;
?>
<div class="tees-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h2>'.Yii::t('igolf', 'Hole').' '.$model->position.'</h2>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
			'par',
			'si',
			'length',
        ],
    ]) ?>

	<?=	$this->render('../media/_add', [
		'model' => $model,
	])?>

</div>
