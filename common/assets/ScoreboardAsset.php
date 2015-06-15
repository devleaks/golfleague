<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * This is the asset class for Scoreboard display.
 */
class ScoreboardAsset extends AssetBundle
{
    public $sourcePath = '@common/assets';

    public $css = [
    	'css/scorecard.css',
        'css/scoreboard.css',
    ];

    public $js = [
        'js/scoreboard.js',
    ];

}
