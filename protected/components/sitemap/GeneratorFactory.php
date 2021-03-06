<?php
/**
 * @author Vladimir Utenkov <utenkov@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package frontend.components.sitemapXml
 */
class GeneratorFactory extends CComponent
{
  /**
   * @var ILocationGenerator[]
   */
  private $_generators;

  /**
   * @param string $pathToGenerators
   * @param FController $controller
   */
  public function __construct($pathToGenerators, FController $controller)
  {
    $generatorNames = new LocationGeneratorFileFilter(new DirectoryIterator($pathToGenerators));

    /** @var $generators ILocationGenerator[] */
    $generators = array();

    /** @var $name DirectoryIterator */
    foreach($generatorNames as $name)
    {
      $className = $name->getBasename('.php');
      $generator = new $className($controller);

      if( $generator instanceof ILocationGenerator )
      {
        $generators[] = $generator;
      }
    }

    $this->_generators = $generators;
  }

  /**
   * @return ILocationGenerator[]
   */
  public function getGenerators()
  {
    return $this->_generators;
  }
}