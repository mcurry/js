<?php
class JsController extends JsAppController {
  var $name = 'Js';
  var $uses = array('Js.JsLang');

  function lang($lang, $jsFile=null) {
    $cache = true;
    if (Configure::read('debug')) {
      $cache = false;
    }

    if ($js = $this->JsLang->i18n($lang, $jsFile, $cache)) {
      header('Content-type: text/javascript');
      echo $js;
      die;
    } else {
      $this->cakeError('error404');
    }
  }
}

?>