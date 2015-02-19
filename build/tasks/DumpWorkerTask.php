<?php
/**
 * Базовый класс для работы с дампами БД
 *
 * @author Fedor A Borshev <fedor@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package build.tasks
 */
require_once "phing/Task.php";

class DumpWorkerTask extends Task
{
  protected $file;

  public function setFile($file)
  {
    $this->file = $file;
  }

  public function main()
  {
    if( !file_exists($this->file) )
    {
      throw new BuildException('Cannot open dump file '.$this->file);
    }

    $fileData = file($this->file);
    $fileData = $this->parse($fileData);

    file_put_contents($this->file, $fileData);
  }

  protected function isTriggerOrRoutine($str)
  {
    if( preg_match('/TRIGGER|PROCEDURE/', $str) )
      return true;

    return false;
  }

  protected function isView($str)
  {
    if( preg_match('|^/\*\!50013 DEFINER|', $str) and preg_match('/SQL SECURITY/', $str) )
      return true;

    return false;
  }

  protected function replaceUser($str)
  {
    return preg_replace('/DEFINER=`[^\`]*`@`[^\`]*`/', 'DEFINER=CURRENT_USER', $str);
  }

  protected function replaceUserReverse($str)
  {
    $user = '`'.$this->project->getProperty('db.mysqlUser').'`@`'.$this->project->getProperty('db.mysqlHost').'`';

    return preg_replace('/DEFINER=CURRENT_USER/', "DEFINER=$user", $str);
  }

  protected function removeAutoIncrement($str)
  {
    if( preg_match('/ENGINE=/', $str) )
      return preg_replace('/AUTO_INCREMENT=[0-9]+\ /', '', $str);

    return $str;
  }

  protected function removeComments($str)
  {
    $comments = array(
      '^-- Host: .+Database: .+$',
      '^-- Dump completed on .+$',
      '^-- Dumping routines for database \'.+\'$',
    );

    foreach($comments as $pattern)
      $str = preg_replace('/'.$pattern.'/', '', $str);

    return $str;
  }
}