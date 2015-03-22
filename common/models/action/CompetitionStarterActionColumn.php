<?php

namespace common\models\action;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * This is the utility class to draw action buttons for "competitions".
 *
 */
class CompetitionStarterActionColumn extends CompetitionActionColumn
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
        if (!isset($this->buttons['tees'])) {
            $this->buttons['tees'] = function ($url, $model) {
		        return $this->createAnchor($url, 'tees');
            };
        }
        if (!isset($this->buttons['approve'])) {
            $this->buttons['approve'] = function ($url, $model) {
		        return $this->createAnchor($url, 'approve');
            };
        }
        if (!isset($this->buttons['reject'])) {
            $this->buttons['reject'] = function ($url, $model) {
		        return $this->createAnchor($url, 'reject');
            };
        }
        if (!isset($this->buttons['team'])) {
            $this->buttons['team'] = function ($url, $model) {
		        return $this->createAnchor($url, 'team');
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
			case 'registration':
		    	$url = Url::to(['registration/bulk',
		                     'id' => $model->id ]);
				break;
			case 'pending':
		    	$url = Url::to(['registration/pending',
		                     'id' => $model->id ]);
				break;
			case 'start':
		    	$url = Url::to(['flight/competition',
		                     'id' => $model->id ]);
				break;
			case 'tees':
		    	$url = Url::to(['registration/tees',
		                     'id' => $model->id ]);
				break;
			case 'update':
		        $url = Url::to(['competition/update',
		                     'id' => $model->id ]);
				break;
			case 'approve':
		        $url = Url::to(['registration/approve',
		                     'id' => $model->id ]);
				break;
			case 'reject':
		        $url = Url::to(['registration/reject',
		                     'id' => $model->id ]);
				break;
			case 'team':
		        $url = Url::to(['registration/team',
		                     'id' => $model->id ]);
				break;
			default:
			case 'view':
		        $url = Url::to(['competition/view',
		                     'id' => $model->id ]);
				break;
		}
		return $url;
    }


}
