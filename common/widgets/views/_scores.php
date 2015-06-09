<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use common\models\Registration;
use common\models\Competition;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="scorecard">

     <?= GridView::widget([
		'options' => ['id' => 'registration'],
        'dataProvider' => $dataProvider,
		'panel'=>[
	        'heading' => '<h4>'.$this->title.'</h4>',
			'footer' => false,
	    ],
        'columns' => [
			'score',
			'putts',
			'drive',
			'drive_length',
			'regulation',
			'penalty',
			'sand',
			'approach',
			'approach_length',
			'putt_length',
			'putt',
        ],
    ]); ?>

</div>