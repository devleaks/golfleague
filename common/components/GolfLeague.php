<?php

namespace common\components;

use common\models\League;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\di\Container;

/**
 * GolfLeague Main Component. Contains global league behavior components, options, variables...
 *
 * @author PierreM
 */
class GolfLeague extends Component
{
	/** Default values */
	const DAYS_BEFORE = 28;
	const HANDICAP_SYSTEM = 'common\models\handicap\HandicapEGA';
	
	public $league;

	public $handicapSystem;
	public $handicap_system;
	
	public $league_roles;
	public $tee_colors;
	public $days_before;
	
	public $flightMethods;
	public $teamMethods;
	public $matchMethods;

	public function init() {
		parent::init();
		
		// $this->league = League::findOne(Yii::$app->user->league_id);		
		
		$r = new \ReflectionClass($this->handicapSystem ? $this->handicapSystem : self::HANDICAP_SYSTEM);
		$this->handicap_system = $r->newInstance();
		$this->league_roles = [
			'admin' => 'Site Administrator',
			'manager' => 'League Manager',
			'starter' => 'Starter',
			'scorer' => 'Scorer',
			'golfer' => 'Golfer',
			'golferplus' => 'Golfer+',
			'marker' => 'Marker'
		];
		$this->tee_colors = [
			'yellow' => Yii::t('golf', 'Yellow'),
			'black' => Yii::t('golf', 'Black'),
			'white' => Yii::t('golf', 'White'),
			'blue' => Yii::t('golf', 'Blue'),
			'red' => Yii::t('golf', 'Red'),
			'gold' => Yii::t('golf', 'Gold'),
			'silver' => Yii::t('golf', 'Silver'),
			'orange' => Yii::t('golf', 'Orange'),
			'pink' => Yii::t('golf', 'Pink'),
		];
		$this->days_before = self::DAYS_BEFORE;
	}

	/**
	 * Parameter string is name=value;name=value. If parameter name is repeated, values are collected in array of values.
	 *
	 * @return array(name, value) pairs where name is scalar, and value is mixed scalar/array.
	 */
	public static function getParameters($str) {
		if($str) {
		  # result array
		  $arr = array();

		  # split on outer delimiter
		  $pairs = explode(';', $str);

		  # loop through each pair
		  foreach ($pairs as $i) {
		    # split into name and value
		    list($name,$value) = explode('=', $i, 2);

		    # if name already exists
		    if( isset($arr[$name]) ) {
		      # stick multiple values into an array
		      if( is_array($arr[$name]) ) {
		        $arr[$name][] = $value;
		      }
		      else {
		        $arr[$name] = array($arr[$name], $value);
		      }
		    }
		    # otherwise, simply stick it in a scalar
		    else {
		      $arr[$name] = $value;
		    }
		  }

		  # return result array
		  return $arr;
		}
		return [];
	}
	
}