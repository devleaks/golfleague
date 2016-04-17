<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;

/**
 *	Constant behavior/trait inspects a class and extract all constants whose name match a supplied pattern.
 */
trait Constant {

	/**
	 * getConstants returns constants defined in a class with name that starts with supplied prefix.
	 *
	 * @param  $constant_prefix first characters of constant name
	 *
	 * @return array of key,localized value.
	 */
    function getLocalizedConstants($constant_prefix) {
		Yii::trace(__CLASS__, 'trait');
        $oClass = new \ReflectionClass(__CLASS__);
        $result = [];
		foreach($oClass->getConstants() as $k => $v)
			if(strpos($k, $constant_prefix) === 0)
				$result[$v] = Yii::t('golf', ucfirst(strtolower($v)));
		//Yii::trace(print_r($result, true), 'trait');
        return $result;
    }

	/**
	 * getConstants returns constants defined in a class with name that starts with supplied prefix.
	 *
	 * @param  $constant_prefix first characters of constant name
	 *
	 * @return array of key,localized value.
	 */
    function getConstants($constant_prefix) {
		//Yii::trace(__CLASS__, 'trait');
        $oClass = new \ReflectionClass(__CLASS__);
        $result = [];
		foreach($oClass->getConstants() as $k => $v)
			if(strpos($k, $constant_prefix) === 0)
				$result[$v] = $v;
        return $result;
    }

	/**
	 * Generates colored labels for Document. Color depends on document status.
	 *
	 * @param $what Column to generate a label for. Must be a valid attribute. No check!
	 *
	 * @return string HTML fragment
	 */
	function makeLabel($what) {
		if(isset($this->$what)) {
			$colors = $this->getLabelColors($what);
			$color  = in_array($this->$what, array_keys($colors)) ? $colors[$this->$what] : 'default';
			return '<span class="label label-'.$color.'">'.Yii::t('gip', $this->$what).'</span>';
		}
		return '<span class="label label-default">'.Yii::t('gip', 'Property {0} not found for {1}.', [$what, $this::className()]).'</span>';
	}


}
