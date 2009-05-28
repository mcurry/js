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
    if(Configure::read('Js.paths')) {
      $this->paths = array_merge($this->paths, Configure::read('Js.paths'));
    }
  }

  function i18n($lang, $jsFile=null, $cache=false) {
    $this->init();
    
    $cacheFile = $jsFile;
    if ($jsFile == null && preg_match('/\.js$/i', $lang)) {
      $cacheFile = $lang;
      $jsFile = 'lang.js';
      $lang = str_replace('.js', '', $lang);
    } else {
      $cacheFile = $lang . DS . $cacheFile;
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
      
      if($cache) {
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