<?php

namespace backend\assets;

use yii\web\AssetBundle;

class FlightsAsset extends AssetBundle
{
    public $sourcePath = '@backend/assets';

    public $css = [
        'css/flights.css',
    ];

    public $js = [
        'js/flights.js',
    ];

    public $depends = [
        'yii\jui\SortableAsset',
//        'yii\jui\DraggableAsset',
//        'yii\jui\DroppableAsset',
    ];
 
}
