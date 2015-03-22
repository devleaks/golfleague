<?php

namespace common\models\action;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * This is the utility class to draw action buttons for "competitions".
 *
 */
class CompetitionActionColumn extends \yii\grid\ActionColumn
{
	public $actions;
	
	protected function prependTemplate($template) {
		$this->template = '{' . $template . '} ' . $this->template;
	}
	
	protected function appendTemplate($template) {
		$this->template =  $this->template . ' {' . $template . '}';
	}
	
	protected function removeTemplate($template) {
		$this->template = trim(str_replace('  ', ' ', str_replace('{'.$template.'}', '', $this->template)));
	}
	
	protected function createAnchor($url, $action) {
		return Html::a('<span class="glyphicon glyphicon-'.$this->actions[$action]['icon'].'"></span>', $url, [
            'title' => Yii::t('golfleague', $this->actions[$action]['label']),
            'data-pjax' => '0',
        ]);
	}
	
	/** Main */

	public function init() {
		parent::init();
		$this->actions = [
			'create'		=> ['icon' => 'plus',		'label' => 'Add'],
			'update'		=> ['icon' => 'pencil',		'label' => 'Modify'],
			'delete'		=> ['icon' => 'remove',		'label' => 'Delete'],
			'approve'		=> ['icon' => 'thumbs-up',	'label' => 'Approve Registration'],
			'deregister'	=> ['icon' => 'remove',		'label' => 'Deregister'],
			'pending'		=> ['icon' => 'check',		'label' => 'Approve Registration'],
			'register'		=> ['icon' => 'plus',		'label' => 'Register'],
			'registration'	=> ['icon' => 'calendar',	'label' => 'Registrations'],
			'reject'		=> ['icon' => 'thumbs-down','label' => 'Reject Registration'],
			'result'		=> ['icon' => 'glass',		'label' => 'Flights'],
			'start'			=> ['icon' => 'flag',		'label' => 'Flights'],
			'team'			=> ['icon' => 'share',		'label' => 'Teams'],			
			'tees'			=> ['icon' => 'filter',		'label' => 'Tees'],
		];
	}
}
