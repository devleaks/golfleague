<?php

namespace common\models\action;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Golfer;

/**
 * This is the utility class to draw action buttons for "competitions".
 *
 */
class CompetitionGolferActionColumn extends CompetitionActionColumn
{
    protected function initDefaultButtons()
    {
		parent::initDefaultButtons();
		unset($this->buttons['update']);
		$this->removeTemplate('update');
		unset($this->buttons['delete']);
		$this->removeTemplate('delete');
		
		$this->appendTemplate('register');
        if (!isset($this->buttons['register'])) {
            $this->buttons['register'] = function ($url, $model) {
				$me = Golfer::me();
		        if(!$me)
		            return null;
		 		$action = $model->registered($me) ? 'deregister' : 'register';
		        return $this->createAnchor($url, $action);
            };
        }
	}


    /**
     * @inheritdoc
     */
    public function createUrl($action, $model, $key, $index)
	{
		$url = null;
		switch($action) {
			case 'view':
		        $url = Url::to(['registration/view',
		                     'competition_id' => $model->id ]);
				break;
			case 'register':
			case 'deregister':
				$me = Golfer::me();
		        if(!$me)
		            return null;

		        $url = ($action == 'register') ?
		            Url::to(['registration/'.($model->registered($me) ? "deregister" : "register"),
		                     'competition_id' => $model->id ]) :
		            null;
		}
		return $url;
    }


}
