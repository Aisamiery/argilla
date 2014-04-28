<?php
/**
 * @author Sergey Glagolev <glagolev@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2013 Shogo
 * @license http://argilla.ru/LICENSE
 */
class RequestRedirectComponentTest extends CDbTestCase
{
  protected $fixtures = array(
    'seo_redirect' => 'Redirect'
  );

  public function testDoNotMakeSlashRedirect()
  {
    $component = new RequestRedirectComponent();

    $component->setRequest('testUrl/');
    $component->init();
    $this->assertNotNull($component);

    $component->setRequest('testUrl.html');
    $component->init();
    $this->assertNotNull($component);
  }

  /**
   * @expectedException TRedirectException
   * @expectedExceptionMessage Location: testUrl/
   */
  public function testMakeSlashRedirect()
  {
    $component = new RequestRedirectComponent('testUrl');
    $component->init();
  }

  /**
   * @expectedException TRedirectException
   * @expectedExceptionMessage Location: testUrl/?param1=value1
   */
  public function testMakeSlashRedirectWithGet()
  {
    $component = new RequestRedirectComponent('testUrl?param1=value1');
    $component->init();
  }

  /**
   * @expectedException TRedirectException
   * @expectedExceptionMessage Location: /
   */
  public function testMakeIndexRedirect1()
  {
    $component = new RequestRedirectComponent('/index.php');
    $component->init();
  }

  /**
   * @expectedException TRedirectException
   * @expectedExceptionMessage Location: /
   */
  public function testMakeIndexRedirect2()
  {
    $component = new RequestRedirectComponent('/index.php/');
    $component->init();
  }

  /**
   * @expectedException TRedirectException
   * @expectedExceptionMessage Location: /testUrl/
   */
  public function testMakeIndexRedirect()
  {
    $component = new RequestRedirectComponent('/index.php/testUrl/');
    $component->init();
  }

  /**
   * @expectedException TRedirectException
   * @expectedExceptionMessage Location: /elki_palki/
   * @expectedExceptionCode 301
   */
  public function testFindByKey()
  {
    $component = new RequestRedirectComponent('/palki/');
    $component->init();
    $component->processRequest();
  }

  /**
   * @expectedException TRedirectException
   * @expectedExceptionMessage Location: /
   * @expectedExceptionCode 301
   */
  public function testIndexRedirect()
  {
    $component = new RequestRedirectComponent('/argilla/');
    $component->init();
    $component->processRequest();
  }

  public function testFindByPattern()
  {
    $component = new RequestRedirectComponent('/lyzhnoe_snaryazhenie/2/');
    $component->init();
    $component->processRequest();

    $this->assertEquals('/aksessuary_dlya_lyzh/2/', $_SERVER['REQUEST_URI']);
  }

  /**
   * @expectedException CHttpException
   * @expectedExceptionMessage 404 - Not found
   */
  public function testOriginExists()
  {
    $component = new RequestRedirectComponent('/lyzhi_begovie/');
    $component->init();
    $component->processRequest();
  }

  /**
   * @expectedException CHttpException
   * @expectedExceptionMessage 404 - Not found
   */
  public function testFind404()
  {
    $component = new RequestRedirectComponent('/figurnye/');
    $component->init();
    $component->processRequest();
  }
}