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
	 * @return string HTML fragment
	 */
	function makeLabel($str) {
		$colors = $this->getLabelColors();
		$color  = in_array($str, array_keys($colors)) ? $colors[$str] : 'default';
		return '<span class="label label-'.$color.'">'.Yii::t('golf', $str).'</span>';
	}


}
