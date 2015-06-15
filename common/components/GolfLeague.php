<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * GolfLeague Main Component. Contains global league behavior components, options, variables...
 *
 * @author PierreM
 */
class GolfLeague extends Component
{
	public $handicapSystem;
	public $handicap_system;
	
	public function init() {
		parent::init();
		$r = new \ReflectionClass($this->handicapSystem);
		$this->handicap_system = $r->newInstance();
	}
}