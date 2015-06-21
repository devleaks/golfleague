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