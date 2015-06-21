<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\Registration;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Scorecards for {0}', $competition->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Scorecards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?=	$this->render('_scorecards', [
		'searchModel' => $searchModel,
		'dataProvider' => $dataProvider,
		'competition' => $competition,
	])
?>