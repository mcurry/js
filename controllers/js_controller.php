<?php
class JsController extends JsAppController {
	var $name = 'Js';
	var $uses = array('Js.JsLang');

	function beforeFilter() {
		parent::beforeFilter();
		if(!empty($this->Auth)) {
			$this->Auth->allow('lang');
		}
	}

	function lang() {
		$cache = true;
		if (Configure::read('debug')) {
			$cache = false;
			Configure::write('debug', 0);
		}

		if ($js = $this->JsLang->i18n($this->params['pass'], $cache)) {
			header('Content-type: text/javascript');
			echo $js;
			die;
		} else {
			$this->cakeError('error404');
		}
	}
}

?>