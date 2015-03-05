<?php
/**
 * @author Nikita Melnikov <melnikov@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package backend.modules.gallery
 */
class GalleryModule extends BModule
{
  public $defaultController = 'BGallery';

  public $name = 'Фотогалерея';

  public function getThumbsSettings()
  {
    return array(
      'gallery' => array(
        'origin' => array(1280, 1024),
        'small' => array(480, 480),
    ));
  }
}