<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\builder\TabularForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Points');

$buttons = '<div class="btn-group">';
$buttons .= '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'. Yii::t('igolf', 'Add points') . ' <span class="caret"></span></button>';
$buttons .= '<ul class="dropdown-menu" role="menu">';
foreach([1, 2, 5, 10, 20, 50] as $cnt)
    $buttons .= '<li>'.Html::a(Yii::t('igolf', 'Add {0, number} points', $cnt), ['rule/addpoints', 'rule_id' => $rule->id, 'count' => $cnt]).'</a></li>';
$buttons .= '</ul>';
$buttons .= '</div> ';

if( $rule->getPoints()->count() > 0 ) {
	$buttons .= Html::submitButton(Yii::t('igolf', 'Update Points'), ['class'=>'btn btn-primary']);
}

?>
<div class="point-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php
        $form = ActiveForm::begin(['action'=> Url::to(['/admin/point/updates'])]);

        echo TabularForm::widget([
            'form' => $form,
            'dataProvider' => $dataProvider,
            'serialColumn' => false,
            'actionColumn' => [
				'template' => '{delete}',
				'controller' => 'point',
	            'buttons' => [
	                'delete' => function ($url, $model) {
	                    return Html::a('<i class="glyphicon glyphicon-trash"></i>', $url, [
	                        'class' => 'btn',
	                        'data-confirm' => Yii::t('igolf', 'Are you sure to delete this point?'),
	                        'title' => Yii::t('igolf', 'Delete'),
	                    ]);
	                },
	            ],
				'urlCreator' => function ($action, $model, $key, $index) {
					$url = '';
					switch($action) {
						case 'delete':
							$url = Url::to(['point/delete-get', 'id' => $model->id]);
							break;
					}
					return $url;
				}
			],
            'checkboxColumn' => false,
		    'gridSettings' => [
		        'floatHeader' => true,
		        'panel' => [
		            'heading' => '<h4>'.$this->title.'</h4>',
		            'footer'=> $buttons
		        ]
		    ], 
            'attributes' => [
                'position' => [ 'type' => TabularForm::INPUT_TEXT, ],
                'points' => [ 'type' => TabularForm::INPUT_TEXT, ],
			],
        ]);

        ActiveForm::end();
    ?>

</div>
