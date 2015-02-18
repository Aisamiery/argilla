<?php
/**
 * @author Nikita Melnikov <melnikov@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package ext.mainscript
 */
class ScriptsFileFinder
{
  public static $root;

  /**
   * Файлы для сборки
   *
   * @var array
   */
  private $files = array();

  /**
   * Список скриптов для упаковки с условиями
   *
   * @var array
   */
  public $availableScripts = array(
    'jquery' => array(
      'dir_name' => '/js/src/vendor',
      'pattern'  => '/^jquery[\-\d\.]*.js$/',
    ),

    'jquery-ui' => array(
      'dir_name' => '/js/src/vendor',
      'pattern'  => '/^jquery-ui[\-\d\.]*.js$/',
    ),

    'jquery-plugins' => array(
      'dir_name' => '/js/src/vendor/jquery_plugins',
      'pattern'  => '/^.+\.js$/'
    ),

    'jquery-ui-widgets' => array(
      'dir_name' => '/js/src/vendor/jquery_ui_widgets',
      'pattern'  => '/^.+\.js$/'
    ),

    'vendor' => array(
      'dir_name' => '/js/src/vendor',
      'pattern'  => '/^.+\.js$/'
    ),

    'other' => array(
      'dir_name' => '/js/src',
      'pattern'  => '/^.+\.js$/'
    ),
  );

  /**
   * Singleton
   *
   * @var self
   */
  private static $instance = null;

  /**
   * Singleton
   *
   * @return ScriptsFileFinder
   */
  public static function getInstance()
  {
    if( empty(self::$instance) )
      self::$instance = new ScriptsFileFinder();

    return self::$instance;
  }

  private function __construct()
  {
    if( empty(self::$root) )
      self::$root = Yii::getPathOfAlias('webroot');

    $this->initFiles();
  }

  /**
   * Данный метод лучше использовать для получения корня сайта,
   * чтобы убедиться что инициализация класса прошла успешно
   *
   * @return string
   */
  public function getRoot()
  {
    return self::$root;
  }

  public function getFiles()
  {
    return $this->files;
  }

  /**
   * Инициализация файлов для упаковки по условиям $availableScripts
   */
  public function initFiles()
  {
    foreach( $this->availableScripts as $script )
    {
      $files = $this->getAllFiles($script['dir_name']);

      foreach( $files as $file )
      {
        $fileName = pathinfo($file, PATHINFO_BASENAME);

        if( preg_match($script['pattern'], $fileName) && !in_array($fileName, ScriptAbstractCreator::$scripts) )
        {
          $this->files[$file] = $file;
        }
      }
    }
  }

  /**
   * Получение всех файлов в директории скриптов
   *
   * $additionalPath добавляет путь к $fullPath для поиска скриптов
   *
   * @param string $additionalPath
   *
   * @return array
   */
  protected function getAllFiles($additionalPath = null)
  {
    $path = self::$root.$additionalPath;

    if( !file_exists($path) )
      return array();

    $files = CFileHelper::findFiles($path, array('level' => 0));

    usort($files, function($a, $b){
      return strcmp(pathinfo($a, PATHINFO_FILENAME), pathinfo($b, PATHINFO_FILENAME));
    });

    return $files;
  }
}
