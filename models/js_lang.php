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
    if(!is_array($params)) {
      $params = explode('/', trim($params, '/'));  
    }
    
    $this->init();

    $cacheFile = implode(DS, $params);
    $params = $this->normalize($params);
    $jsFile = $this->parseFile($params);
    $lang = $this->parseLang($params);
    

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
  
  function parseFile($file) {
    if (count($file) == 1 && preg_match('/\.js$/i', $file[0])) {
      return 'lang.js';
    } else {
      unset($file[0]);
      return implode(DS, $file);
    }
  }
  
  function parseLang($file) {
    if (count($file) == 1 && preg_match('/\.js$/i', $file[0])) {
      return str_replace('.js', '', $file[0]);
    } else {
      return $file[0];
    }
  }
  
  function normalize($file) {
    if(!is_array($file)) {
      $file = explode('/', trim($file, '/'));  
    }
    
    if($file[0] == 'lang') {
      unset($file[0]);
      $file = array_values($file);
    }
    
    return $file;
  }

  function write($path, $content) {
    $outJsFile = $this->paths['js'] . 'lang' . DS . str_replace('/', DS, $path);
    App::import('Core', 'File');
    $File = new File($outJsFile, true);
    $File->write($content);
  }
}
?>