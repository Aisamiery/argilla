<?php
/**
 * @author Vladimir Utenkov <utenkov@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package frontend.components.sitemap.generators
 */
class NewsLocation extends LocationBase
{
  /**
   * @param CController $controller
   */
  public function __construct(CController $controller)
  {
    parent::__construct($controller);

    $this->_modelSource = new CDataProviderIterator(new CActiveDataProvider('News'));
  }

  /**
   * @return string
   */
  public function current()
  {
    /** @var $current News */
    $current = $this->_modelSource->current();

    return $this->_controller->createAbsoluteUrl($this->getRoute(), array('section' => $current->section->url, 'url' => $current->url));
  }

  /**
   * @return string
   */
  public function getRoute()
  {
    return 'news/one';
  }
}