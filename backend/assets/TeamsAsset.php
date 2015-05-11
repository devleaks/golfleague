<?php

namespace backend\assets;

use yii\web\AssetBundle;

class TeamsAsset extends AssetBundle
{
    public $sourcePath = '@backend/assets';

    public $css = [
        'css/flights.css',
    ];

    public $js = [
        'js/teams.js',
    ];

    public $depends = [
        'yii\jui\JuiAsset',
    ];
 
}
