<?php
/**
 * Created by PhpStorm.
 * User: Alexey Sсhevchenko <ivanovosity@gmail.com>
 * Date: 28.08.16
 * Time: 17:45
 */

namespace modules\blog\widgets\tree_menu;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\Html;
use modules\blog\models\BlogCategory;
use modules\blog\widgets\tree_menu\assets\TreeMenuAsset;

class TreeMenuWidget extends Widget
{
    public $status = true;
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
                echo Html::beginTag('ul', ['id' => $this->id, 'class' => 'ul-treefree ul-dropfree']) . PHP_EOL;
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
                $array[] = Html::beginTag('li') . PHP_EOL;
                $array[] = self::getItemActive($category) . PHP_EOL;
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
     * @return string
     */
    private function getItemActive($data)
    {
        if (Yii::$app->request->get('category') == $data->slug) {
            return '<strong>' . $data->name . '</strong>';
        } else {
            return Html::a($data->name, ['default/category', 'category' => $data->slug], ['rel' => 'nofollow']);
        }
    }

    /**
     * Register resource
     */
    private function registerAssets()
    {
        $view = $this->getView();
        TreeMenuAsset::register($view);
    }
}