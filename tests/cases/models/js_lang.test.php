<?php
App::import('Model', array('Js.JsLang'));

class JsLangTestCase extends CakeTestCase {
  var $JsLang = null;

  var $www_root = null;
  var $js_root = null;

  function startCase() {
    $testAppRoot = ROOT . DS . 'app' . DS . 'plugins' . DS . 'js' . DS . 'tests' . DS . 'test_app' . DS;
    $this->www_root =  $testAppRoot . 'webroot' . DS;
    $this->js_root =  $this->www_root . 'js' . DS;


    $this->JsLang = new JsLang();

    Configure::write('Js.paths', array('www_root' => $this->www_root,
                                       'js' => $this->js_root,
                                       'source' => $this->js_root . 'source' . DS));
    Configure::write('localePaths', array($testAppRoot . 'locale'));
  }

  function startTest() {
    Configure::delete('Config.language');
  }

  function endCase() {
    App::import('Core', 'Folder');
    $Folder = new Folder();
    $Folder->delete($this->js_root . 'lang');
  }

  function testInstances() {
    $this->assertTrue(is_a($this->JsLang, 'JsLang'));
  }

  function testI18nEn() {
    $result = $this->JsLang->i18n(array('en', 'test.js'));
    $expected = 'alert("Hello World");';
    $this->assertEqual($result, $expected);
  }

  function testI18nEs() {
    $result = $this->JsLang->i18n(array('es', 'test.js'));
    $expected = 'alert("Hola World");';
    $this->assertEqual($result, $expected);
  }

  function testLangEn() {
    $result = $this->JsLang->i18n(array('en.js'));
    $expected = <<<END
var Lang =
  {
  Test : "A test is this"
  };
END;
    $this->assertEqual($result, $expected);
  }

  function testLangEs() {
    $result = $this->JsLang->i18n(array('es.js'));
    $expected = <<<END
var Lang =
  {
  Test : "El test-o"
  };
END;
    $this->assertEqual($result, $expected);
  }

  function testLangEnSub() {
    $result = $this->JsLang->i18n(array('en', 'sub', 'sub.js'));
    $expected = 'alert("JS in sub folder");';
    $this->assertEqual($result, $expected);
  }
  
  function testWrite() {
    $expected = 'alert("Hola World");';
    $this->JsLang->write('es/test.js', $expected);
    $results = file_get_contents($this->js_root . 'lang' . DS . 'es' . DS . 'test.js');
    $this->assertEqual($results, $expected);
  }
}