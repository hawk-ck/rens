<?php

namespace Rens;

class HTML {
	private $js = array();
	private $js_cdn = array();
	private $css = array();
	private $css_cdn = array();
		
	public $head_title;
	public $page_nav;
	public $page_title;
	public $page_subtitle;
	
	public $imgPath;
	
	function __construct(){
		$this->head_title = 'Hello World!';
		$this->imgPath = BASE_PATH . 'img/';
	}

	/* tag <link> & <script> */

	public function addJs($files, $cdn = FALSE){
		if ($cdn){
			if (is_array($files)){
				array_merge($this->js_cdn, $files);
			} elseif (is_string($files)){
				array_push($this->js_cdn, $files);
			}
		} else {
			if (is_array($files)){
				array_merge($this->js, $files);
			} elseif (is_string($files)){
				array_push($this->js, $files);
			}
		}
	}

	public function addCss($files, $cdn = FALSE){
		if ($cdn){
			if (is_array($files)){
				array_merge($this->css_cdn, $files);
			} elseif (is_string($files)){
				array_push($this->css_cdn, $files);
			}
		} else {
			if (is_array($files)){
				array_merge($this->css, $files);
			} elseif (is_string($files)){
				array_push($this->css, $files);
			}
		}
	}

	public function includeJs(){
		$jsData = null;		

		if (count($this->js_cdn) > 0){
			foreach ($this->js_cdn as $f){
				$jsData .= '<script type="text/javascript" src="' . $f . '"></script>';
			}
		}
		if (count($this->js) > 0){
			foreach ($this->js as $f){
				$jsData .= '<script type="text/javascript" src="' . BASE_PATH . 'js/' . $f . '.js"></script>';
			}		
		}
		
		return $jsData;
	}

	public function includeStl(){
		$stlData = null;

		if (count($this->css_cdn) > 0){
			foreach ($this->css_cdn as $f){
				$stlData .= '<link rel="stylesheet" type="text/css" media="all" href="' . $f . '" />';
			}
		}
		if (count($this->css) > 0){
			foreach ($this->css as $f){
				$stlData .= '<link rel="stylesheet" type="text/css" media="all" href="' . BASE_PATH . 'stl/' . $f . '.css" />';
			}
		}

		return $stlData;
	}
}