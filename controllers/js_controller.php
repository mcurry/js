<?php
class JsController extends JsAppController {
  var $name = 'Js';
  var $uses = array('Js.JsLang');

  function lang() {
    $cache = true;
    if (Configure::read('debug')) {
      $cache = false;
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