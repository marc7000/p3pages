<?php

class P3PagesTreeWidget extends CWidget {
	
	public $rootNode = null;
		
	function init(){
	}
	
	function run(){
		$criteria = new CDbCriteria;
		$criteria->condition = "p3PageMeta.treeParent_id <=> :id";
		$criteria->params = array(':id'=>$this->rootNode);
		$criteria->with = array('p3PageMeta');
		$firstLevelNodes = P3Page::model()->findAll($criteria);
		#var_dump($firstLevelNodes);exit;
		$this->renderTree($firstLevelNodes);
	}	
	
	private function renderTree($models){
		echo "<ul>";
		foreach($models AS $model){
			echo "<li>";
			$this->render('tree',array('model'=>$model));
			$this->renderTree($model->getChildren());
			echo "</li>";
		}
		echo "</ul>";
	}
}

?>