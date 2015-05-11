<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\Registration;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Registrations for {0}', $competition->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Registrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?=	$this->render('_index', [
		'searchModel' => $searchModel,
		'dataProvider' => $dataProvider,
		'competition' => $competition,
	])
?>