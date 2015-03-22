<?php

namespace common\models\action;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * This is the utility class to draw action buttons for "competitions".
 *
 */
class CompetitionScorerActionColumn extends CompetitionActionColumn
{
    protected function initDefaultButtons()
    {
		parent::initDefaultButtons();

        if (!isset($this->buttons['registration'])) {
            $this->buttons['registration'] = function ($url, $model) {
		        return $this->createAnchor($url, 'registration');
            };
        }
        if (!isset($this->buttons['pending'])) {
            $this->buttons['pending'] = function ($url, $model) {
		        return $this->createAnchor($url, 'pending');
            };
        }
        if (!isset($this->buttons['start'])) {
            $this->buttons['start'] = function ($url, $model) {
		        return $this->createAnchor($url, 'start');
            };
        }
        if (!isset($this->buttons['result'])) {
            $this->buttons['result'] = function ($url, $model) {
		        return $this->createAnchor($url, 'result');
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
			case 'result':
		    	$url = Url::to(['competition/update-scores',
		                     'id' => $model->id ]);
				break;
			case 'update':
		        $url = Url::to(['competition/update',
		                     'id' => $model->id ]);
				break;
			case 'view':
		        $url = Url::to(['competition/view',
		                     'id' => $model->id ]);
				break;
		}
		return $url;
    }


}
