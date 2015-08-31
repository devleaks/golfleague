<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * This is the asset class for tournament brackets display.
 * Note: Library is not available trought distribution mechanism (npm, bower, composer...)
 */
class BracketsAsset extends AssetBundle
{
    public $sourcePath = '@common/assets/bracketsjs';

    public $js = [
        'js/brackets.js',
    ];

}
