<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to print debug info in views.
 *
 */
class DebugInfo extends Model
{
	public $apphomedir;
	
	public function init() {
		$this->apphomedir = Yii::getAlias('@app');
	}


	public static function lastCommit() {
		$apphomedir = Yii::getAlias('@app');
		return `cd $apphomedir ; git describe --tags`;
	}

    /**
     * Print debug info in title
     */
    public static function title($str)
    {
		return $str . (YII_ENV_DEV ? ' –DEV='.self::lastCommit() : '') . (YII_DEBUG ? '–DEBUG' : '');
    }

    /**
     * Print debug info in footer
     */
    public static function footer($str)
    {
		$apphomedir = Yii::getAlias('@app');
		$ret = '<small> — Version '.`cd $apphomedir ; git describe --tags`;
		if(YII_DEBUG) {
			$ret .= ' — Last commit: '.`git log -1 --format=%cd --relative-date`;
			$ret .= ' — '.`hostname`;
			$ret .= ' — '.Yii::$app->getDb()->dsn;
		}
		return $str . $ret;
    }

}
