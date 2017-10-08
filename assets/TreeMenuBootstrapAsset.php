<?php

namespace modules\blog\widgets\tree_menu\assets;

use yii\web\AssetBundle;

/**
 * Class TreeMenuBootstrapAsset
 * @package modules\blog\widgets\tree_menu\assets
 */
class TreeMenuBootstrapAsset extends AssetBundle
{
    public $sourcePath = '@modules/blog/widgets/tree_menu/assets/src/tree_menu_bootstrap';

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