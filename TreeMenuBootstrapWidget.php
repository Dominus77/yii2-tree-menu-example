<?php
/**
 * Created by PhpStorm.
 * User: Alexey Sсhevchenko <ivanovosity@gmail.com>
 * Date: 02.09.16
 * Time: 10:09
 */

namespace modules\blog\widgets\tree_menu;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\View;
use modules\blog\models\BlogCategory;
use modules\blog\widgets\tree_menu\assets\TreeMenuBootstrapAsset;

class TreeMenuBootstrapWidget extends Widget
{
    public $status = true;

    /**
     * @var array
     * @val openedClass => glyphicon-folder-open, glyphicon-chevron-right
     * @val closedClass => glyphicon-folder-close, glyphicon-chevron-down
     */
    public $jsOptions = [];

    public $id;

    private $depthStart = 1;

    public function init()
    {
        parent::init();
        $this->id = $this->id ? $this->id : $this->getId();
        $this->status = $this->status ? true : false;
        $this->registerAssets();
    }

    public function run()
    {
        if ($this->status) {
            if (is_array($tree = self::getRenderTree())) {
                echo Html::beginTag('ul', ['id' => $this->id]) . PHP_EOL;
                foreach ($tree as $items) {
                    echo $items . PHP_EOL;
                }
            }
        }
    }

    /**
     * @return BlogCategory[]|array|bool
     */
    protected function getData()
    {
        $model = new BlogCategory();
        if ($this->depthStart == 0) {
            $query = $model->find()
                ->where(['status' => BlogCategory::STATUS_PUBLISH])
                ->orderBy(['lft' => SORT_ASC])
                ->all();
        } else {
            $query = $model->find()
                ->where(['status' => BlogCategory::STATUS_PUBLISH])
                ->andWhere('depth > 0')
                ->orderBy(['lft' => SORT_ASC])
                ->all();
        }
        return $query ? $query : false;
    }

    /**
     * Render List Tree
     * @return array
     */
    protected function getRenderTree()
    {
        $array = [];
        if ($query = self::getData()) {
            $depth = $this->depthStart;
            $i = 0;
            foreach ($query as $n => $category) {
                if ($category->depth == $depth) {
                    $array[] = $i ? Html::endTag('li') . PHP_EOL : '';
                } else if ($category->depth > $depth) {
                    $array[] = Html::beginTag('ul') . PHP_EOL;
                } else {
                    $array[] = Html::endTag('li') . PHP_EOL;
                    for ($i = $depth - $category->depth; $i; $i--) {
                        $array[] = Html::endTag('ul') . PHP_EOL;
                        $array[] = Html::endTag('li') . PHP_EOL;
                    }
                }
                $itemActive = self::getItemActive($category);
                foreach ($itemActive as $item) {
                    $array[] = $item . PHP_EOL;;
                }
                $depth = $category->depth;
                $i++;
            }
            for ($i = $depth; $i; $i--) {
                $array[] = Html::endTag('li') . PHP_EOL;
                $array[] = Html::endTag('ul') . PHP_EOL;
            }
        }
        return $array;
    }

    /**
     * @param $data
     * @return array
     */
    private function getItemActive($data)
    {
        if (Yii::$app->request->get('category') == $data->slug) {
            $array[] = Html::beginTag('li', ['class' => 'selected']);
            $array[] = '<strong>' . $data->name . '</strong>';
            return $array;
        } else {
            $array[] = Html::beginTag('li');
            $array[] = Html::a($data->name, ['default/category', 'category' => $data->slug], ['rel' => 'nofollow']);
            return $array;
        }
    }

    /**
     * @return string
     */
    protected function getTreeJsOptions()
    {
        $object = ArrayHelper::merge([], $this->jsOptions);
        return json_encode($object);
    }

    /**
     * Register resource
     */
    private function registerAssets()
    {
        $view = $this->getView();
        $treeId = $this->id;
        $options = $this->getTreeJsOptions();
        TreeMenuBootstrapAsset::register($view);
        $view->registerJs("
                    $('#$treeId').treed($options);
            ", View::POS_END);
    }
}