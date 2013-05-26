<?php

namespace Rens\controllers;

use Rens\ZPController;

class Window extends ZPController {

	function beforeAction (){
		$this->setTableList(array('window'));

		$this->html->head_title = 'Test';

		//总是先加载绝对路径的文件
		$this->html->addCss('style');
		$this->html->addJs('site');
		$this->html->addJs('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', TRUE);
		
		$this->html->page_title = 'This is the page_title';
		$this->html->page_subtitle = 'sub_title here';
	}

	function index(){
		$this->clearCache();

		foreach ($this->tableList as $t){
			switch ($t){
				case 'window':
					$where = array(
						'id' => array(1, 2)
					);
					break;
				default:
					break;
			}
			$order = array(
				'id'
			);
			$limit = PAGINATE_LIMIT;
			$offset = null;

			$this->modelIns->setTable($t);
			$result = $this->modelIns->search($where, $order, $limit, $offset);

			$this->setPageVal(ucfirst($t), $result);
		}
	}

	function afterAction(){

	}
}