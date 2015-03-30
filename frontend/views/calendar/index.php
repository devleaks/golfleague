<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\Calendar;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Calendar');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-index">

    <?= Calendar::widget([
			'months' => 3,
    ]) ?>

</div>