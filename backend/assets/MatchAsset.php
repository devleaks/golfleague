<?php

namespace backend\assets;

use yii\web\AssetBundle;

class MatchAsset extends AssetBundle
{
    public $sourcePath = '@backend/assets';

    public $css = [
        'css/match.css',
    ];

    public $js = [
    	'js/match.js',
    ];

    public $depends = [
        'yii\jui\JuiAsset',
    ]; 
}
