<?php

use common\models\Location;

use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

//use yii\widgets\ActiveForm;

$model = $facility->location ? $facility->location : new Location();
$model->parseLatLon();

/**
 * @var yii\web\View $this
 * @var common\models\search\Location $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="location-search">

    <?php $form = ActiveForm::begin(['action' => ['address', 'id' => $facility->id]]); ?>

	<?= Html::panel([
		'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-picture"></i> '.Yii::t('golf', 'Location').' </h3>',
		'body' => Form::widget([
		    'model' => $model,
		    'form' => $form,
		    'columns' => 8,
		    'attributes' => [
				'address'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Address...', 'maxlength'=>160], 'columnOptions' => ['colspan'=>6]], 
				'description'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Note...', 'maxlength'=>160], 'columnOptions' => ['colspan'=>6]], 
				'postcode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Postcode...', 'maxlength'=>40], 'columnOptions' => ['colspan'=>2]], 
				'city'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter City...', 'maxlength'=>80], 'columnOptions' => ['colspan'=>4]], 
				'lat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Latitude', 'maxlength'=>80], 'columnOptions' => ['colspan'=>2]], 
				'lon'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Longitude', 'maxlength'=>160], 'columnOptions' => ['colspan'=>2]], 
				'zoom'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Map Zoom', 'maxlength'=>160], 'columnOptions' => ['colspan'=>2]], 
				'country'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Country...', 'maxlength'=>40], 'columnOptions' => ['colspan'=>5]], 
				'map'=>['type'=> Form::INPUT_RAW, 'value'=>function ($model, $index, $widget) { return
					'<div class="col-lg-6">'.
						\ibrarturi\latlngfinder\LatLngFinder::widget([
						    'model' => $model,              // model object
						    'latAttribute' => 'lat',        // Latitude text field id
						    'lngAttribute' => 'lon',        // Longitude text field id
						    'zoomAttribute' => 'zoom',      // Zoom text field id
						    'mapCanvasId' => 'map',         // Map Canvas id
						    'mapWidth' => 450,              // Map Canvas width
						    'mapHeight' => 300,             // Map Canvas mapHeight
						    'defaultLat' => $model->lat ? $model->lat : 50.845415,		// Default latitude for the map
						    'defaultLng' => $model->lon ? $model->lon : 4.348467,       // Default Longitude for the map
						    'defaultZoom' => $model->zoom ? $model->zoom : 10,             // Default zoom for the map
						    'enableZoomField' => true,      // True: for assigning zoom values to the zoom field, False: Do not assign zoom value to the zoom field
						]).
					'</div>';
				}], 
		    ]
	    ]),
	]) ?>

	<?= Html::submitButton(Yii::t('app', 'Save Address'), ['class' => 'btn btn-success']) ?>
	
    <?php ActiveForm::end(); ?>

</div>
