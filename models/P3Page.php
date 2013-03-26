<?php
/**
 * P3Page is the model class for page nodes
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package p3pages.models
 * @category db.ar
 */

// auto-loading fix
Yii::setPathOfAlias('P3Page', dirname(__FILE__));
Yii::import('P3Page.*');

class P3Page extends BaseP3Page {

    const PAGE_ID_KEY = 'pageId';
    const PAGE_NAME_KEY = 'pageName';

    public function get_label() {
        return $this->t('menuName', null, true). " #". $this->id ;
    }

    // Add your model-specific methods here. This file will not be overriden by gtc except you force it.
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function init() {
        return parent::init();
    }

    public function __toString() {
        return (string) $this->layout;
    }

    public function behaviors() {
        return array_merge(
                array(
                #'JSON' => array(
                #'class' => 'P3JSONBehavior',
                #),
                'MetaData' => array(
                    'class' => 'P3MetaDataBehavior',
                    'metaDataRelation' => 'p3PageMeta',
                    'parentRelation' => 'treeParent',
                    'childrenRelation' => 'p3PageMetas',
                    'contentRelation' => 'id0',
                ),
                'Translation' => array(
                    'class' => 'P3TranslationBehavior',
                    'relation' => 'p3PageTranslations',
                    'fallbackLanguage' => (isset(Yii::app()->params['p3.fallbackLanguage'])) ? Yii::app()->params['p3.fallbackLanguage'] : 'en',
                    'fallbackIndicator' => array('menuName'=>' *'),
                    'fallbackValue' => 'Page*',

                //'attributesBlacklist' => array('loadfrom'),
                )
                ), parent::behaviors()
        );
    }

    public function rules() {
        return array_merge(
                array(
                array('route', 'match', 'pattern' => '/"route":"|{}/', 'message' => 'If not empty, route JSON must contain a \'route\' element'),
                ), parent::rules()
        );
    }

    public function createUrl($additionalParams = array(), $absolute = false) {

        if ($this->id == 1) {
            return Yii::app()->homeUrl;
        } elseif (is_array(CJSON::decode($this->route)) && count(CJSON::decode($this->route)) !== 0) {
            $link = CJSON::decode($this->route);
        } elseif ($this->route && $this->route !== "{}") { // omit JSON ediotr defaults
            return $this->route;
        } else {
            $link['route'] = '/p3pages/default/page';
            $link['params'] = CMap::mergeArray($additionalParams, array(P3Page::PAGE_ID_KEY => $this->id, P3Page::PAGE_NAME_KEY => $this->t('seoUrl')));
        }

        if (isset($link['route'])) {
            $params = (isset($link['params'])) ? $link['params'] : array();
            if ($absolute === true)
                return Yii::app()->controller->createAbsoluteUrl($link['route'], $params);
            else
                return Yii::app()->controller->createUrl($link['route'], $params);
        } else {
            Yii::log('Could not determine URL string for P3Page #'.$this->id , CLogger::LEVEL_WARNING);
        }
    }

    public function isActive() {
        if (self::getActivePage() !== null) {
        return (self::getActivePage()->id == $this->id);
        } else {
            return false;
        }
    }

    public function isActiveParent($model = null) {
        if ($model === null) {
            $model = $this;
        }
        if (count($model->p3PageMeta->p3PageMetas)) {
            foreach($model->p3PageMeta->p3PageMetas AS $metaModel) {
                if ((self::getActivePage()) && $metaModel->id0->id === self::getActivePage()->id) {
                    return true;
                }
                if (count($metaModel->p3PageMetas) && $metaModel->id0) {
                    return $this->isActiveParent($metaModel->id0);
                }
            }
        }
        return false;
    }

    public static function getActivePage() {
        static $page;

        if (isset($page)) {
            return $page;
        } elseif (isset($_GET[P3Page::PAGE_ID_KEY])) {
            return $page = P3Page::model()->findByPk($_GET[P3Page::PAGE_ID_KEY]);
        } elseif (isset($_GET[P3Page::PAGE_NAME_KEY])) {
            return $page = P3Page::model()->findByAttributes(array('name' => $_GET[P2Page::PAGE_NAME_KEY]));
        } else {
            // try to find page via route
            $criteria = new CDbCriteria;
            $criteria->condition = "route LIKE :route";
            $criteria->params = array(':route'=>"%".Yii::app()->controller->route."%");
            return $page = P3Page::model()->find($criteria);
        }
    }

    static public function getMenuItems($rootNode) {
        #$models = P3Page::model()->findAll();
        #$rootNode = P3Page::model()->findByAttributes(array('layout'=>'_BootMenu'));
        if (!$rootNode instanceof P3Page) {
            Yii::log('Invalid root node', CLogger::LEVEL_WARNING);
            return array();
        }
        $models = $rootNode->getChildren();
        $items = array();
        foreach ($models AS $model) {
            if ($model->getMenuItems($model) === array( )){
                $items[] = array('label' => $model->t('menuName', null, true), 'url' => $model->createUrl(), 'active' => ($model->isActive() || $model->isActiveParent()));
            } else {
                $items[] = array('label' => $model->t('menuName', null, true), 'url' => $model->createUrl(), 'items' => $model->getMenuItems($model),  'active' => ($model->isActive() || $model->isActiveParent()));
            }
        }
        return $items;
    }

}
