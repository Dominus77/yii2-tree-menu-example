<?php

namespace modules\blog\widgets\tree_menu\assets;

use yii\web\AssetBundle;

/**
 * Class TreeMenuAsset
 * @package modules\blog\widgets\tree_menu\assets
 */
class TreeMenuAsset extends AssetBundle
{
    public $sourcePath = '@modules/blog/widgets/tree_menu/assets/src/tree_menu';

    public $css = [
        'style.css'
    ];

    public $js = [
        'script.js'
    ];

    public $depends = [
        'frontend\assets\AppAsset',
    ];
}