<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use common\models\Registration;
use common\models\Competition;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Registrations');
$this->params['breadcrumbs'][] = $this->title;
?>

<?=	$this->render('_registrations', [
		'searchModel' => $searchModel,
		'dataProvider' => $dataProvider,
		'competition' => null,
	])
?>