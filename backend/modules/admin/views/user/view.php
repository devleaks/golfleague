<?php

use common\models\League;
use common\models\User;

use kartik\detail\DetailView;
use yii2mod\selectize\Selectize;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Golfer */

$this->title = Yii::t('golf', 'User «{0}»', $model->username);
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="golfer-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i>  '.Html::encode($this->title).' </h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
	        [
				'attribute' => 'id',
				'displayOnly' => true,
	        ],
	        [
				'attribute' => 'username',
				'displayOnly' => true,
	        ],
	        'email:email',
	        [
				'attribute' => 'role',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => User::getLocalizedConstants('ROLE_'),
	        ],
	        [
				'label' => Yii::t('golf', 'League'),
				'attribute' => 'league_id',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => [''=>''] + ArrayHelper::map(League::find()->asArray()->all(), 'id', 'name'),
	            'value' => $model->league ? $model->league->name : '',
	        ],
        ],
    ]) ?>

	<?=	$this->render('../media/_add', [
		'model' => $model,
	])?>

</div>
