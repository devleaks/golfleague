<?php

namespace common\assets;

use yii\web\AssetBundle;

class RecurrenceRuleAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/rrule/lib';

    public $js = [
        'rrule.js',
    	'nlp.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ]; 
}
