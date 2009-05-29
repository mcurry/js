<?php
class JsLang extends JsAppModel {
  var $name = 'JsLang';
  var $useTable = false;

  var $initialized = false;
  var $paths = array('source' => '',
                     'js' => JS,
                     'www_root' => WWW_ROOT);

  function init() {
    App::import('Core', 'L10n');

    $this->paths['source'] = JS . 'source' . DS;
    if (Configure::read('Js.paths')) {
      $this->paths = array_merge($this->paths, Configure::read('Js.paths'));
    }
  }

  function i18n($params, $cache=false) {
    $this->init();

    $cacheFile = implode(DS, $params);
    if (count($params) == 1 && preg_match('/\.js$/i', $params[0])) {
      $lang = str_replace('.js', '', $params[0]);
      $jsFile = 'lang.js';
    } else {
      $lang = $params[0];
      unset($params[0]);
      $jsFile = implode(DS, $params);
    }

    $L10n = new L10n();
    if (!$L10n->map($lang)) {
      $lang = null;
    }
    $L10n->get($lang);

    $sourceJsFile = $this->paths['source'] . $jsFile;
    if (file_exists($sourceJsFile)) {
      ob_start();
      include $sourceJsFile;
      $js = ob_get_clean();

      if ($cache) {
        $this->write($cacheFile, $js);
      }

      return $js;
    }

    return false;
  }

  function write($path, $content) {
    $outJsFile = $this->paths['js'] . 'lang' . DS . str_replace('/', DS, $path);
    App::import('Core', 'File');
    $File = new File($outJsFile, true);
    $File->write($content);
  }
}
?>