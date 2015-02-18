<?php
/**
 * @author Nikita Melnikov <melnikov@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package backend.modules.comment
 */
class CommentModule extends BModule
{
  public $name = 'Комментарии';

  public $defaultController = 'BComment';

  public $enabled = false;

  /**
   * @return array
   */
  protected function getExtraDirectoriesToImport()
  {
    return array(
      'backend.modules.user.models.*'
    );
  }
}