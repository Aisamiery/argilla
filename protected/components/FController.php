<?php
/**
 * @author Sergey Glagolev <glagolev@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2014 Shogo
 * @license http://argilla.ru/LICENSE
 * @package frontend.components
 *
 * @mixin CommonBehavior
 * @mixin SeoBehavior
 * @mixin TextBlockBehavior
 * @mixin FControllerBehavior
 *
 * @property Counter[] $counters
 * @property array $copyrights
 * @property array $contacts
 * @property array|string $settings
 *
 * @property FBasket|FCollectionElement[] $basket
 * @property FFavorite|FCollectionElement[] $favorite
 * @property FForm $fastOrderForm
 * @property FForm $callbackForm
 * @property FForm $loginPopupForm
 */
class FController extends CController
{
  public $breadcrumbs = array();

  /**
   * @var array $activeUrl
   */
  public $activeUrl = array();

  /**
   * @var bool
   */
  protected $rememberThisPage;

  public function behaviors()
  {
    return array(
      'seo' => array('class' => 'SeoBehavior'),
      'controller' => array('class' => 'FControllerBehavior'),
      'textBlock' => array('class' => 'TextBlockBehavior'),
      'common' => array('class' => 'CommonBehavior'),
    );
  }

  public function actions()
  {
    return array(
      'captcha' => array(
        'class' => 'FCaptchaAction',
      ),
    );
  }

  public function init()
  {
    Yii::app()->setHomeUrl($this->createAbsoluteUrl('index/index'));
  }

  public function renderOverride($view, $data = null, $return = false, $processOutput = false)
  {
    if( !empty($this->action) && file_exists($this->viewPath.'/'.$this->action->id.'/'.$view.'.php') )
    {
      $this->renderPartial($this->action->id.'/'.$view, $data, $return, $processOutput);
    }
    else if( file_exists($this->viewPath.'/'.$view.'.php') )
    {
      $this->renderPartial($view, $data, $return, $processOutput);
    }
    else
    {
      $this->renderPartial('//'.$view, $data, $return, $processOutput);
    }
  }

  /**
   * @param string $modelClass
   * @param int $id
   *
   * @return CActiveRecord
   * @throws CHttpException
   */
  public function loadModel($modelClass, $id)
  {
    $model = $modelClass::model()->findByPk($id);

    if( $model === null )
      throw new CHttpException(404, "The requested page of {$modelClass} does not exist.");

    return $model;
  }

  /**
   * @param string $view
   * @param null $data
   * @param bool $return
   *
   * @return string|null
   * @throws CException
   */
  public function render($view, $data = null, $return = false)
  {
    $this->onBeforeRender(new CEvent($this, array('data' => $data, 'view' => $view)));

    if( $this->beforeRender($view) )
    {
      $output = $this->renderPartial($view, $data, true);

      if( ($layoutFile = $this->getLayoutFile($this->layout)) !== false )
      {
        $this->onBeforeRenderLayout(new CEvent($this, array('content' => $output)));
        $output = $this->renderFile($layoutFile, array('content' => $output), true);
      }

      $this->afterRender($view, $output);
      $output = $this->processOutput($output);

      if( $return )
        return $output;
      else
        echo $output;
    }

    return null;
  }

  public function onBeforeRender(CEvent $event)
  {
    $this->raiseEvent('onBeforeRender', $event);
  }

  public function onBeforeRenderLayout(CEvent $event)
  {
    $this->raiseEvent('onBeforeRenderLayout', $event);
  }

  /**
   * http://help.yandex.ru/webmaster/?id=1111858#canonical
   *
   * @return string
   */
  public function getCanonicalUrl()
  {
    $request = Yii::app()->request;
    $path = Utils::normalizeUrl('/'.CHtml::encode($request->getPathInfo()));

     if( !Yii::app()->errorHandler->error && $path )
       $path = Yii::app()->urlManager->createPath($path);

    $url = array(
      'host' => $request->getHostInfo(),
      'path' => $path,
      'query' => array(),
    );

    if( Yii::app()->urlManager->rule )
      foreach(Yii::app()->urlManager->rule->canonicalParams as $param)
        if( $value = $request->getParam($param) )
          $url['query'][$param] = $value;

    return Utils::buildUrl($url);
  }

  /**
   * @param bool $cutDefaultParams
   *
   * @return string
   */
  public function getCurrentUrl($cutDefaultParams = true)
  {
    return $this->createUrl($this->id.'/'.$this->action->id, $this->getActionParams($cutDefaultParams));
  }

  /**
   * @param bool $cutDefaultParams
   *
   * @return string
   */
  public function getCurrentAbsoluteUrl($cutDefaultParams = true)
  {
    return $this->createAbsoluteUrl($this->id.'/'.$this->action->id, $this->getActionParams($cutDefaultParams));
  }

  /**
   * @param bool $cutDefaultParams
   *
   * @return string
   */
  public function getActionUrl($cutDefaultParams = true)
  {
    return preg_replace('/\?.*/', '', $this->getCurrentUrl($cutDefaultParams));
  }

  /**
   * @param bool $cutDefaultParams
   *
   * @return array
   */
  public function getActionParams($cutDefaultParams = false)
  {
    $params = $_GET;

    if( $cutDefaultParams )
    {
      $rule = Arr::get(Yii::app()->urlManager->rules, Yii::app()->urlManager->ruleIndex);
      foreach(Arr::get($rule, 'defaultParams', array()) as $key => $value)
        unset($params[$key]);
    }

    return $params;
  }

  /**
   * @param CAction $action
   *
   * @return void
   */
  protected function afterAction($action)
  {
    if( Yii::app()->urlManager->shouldRememberReturnUrl() )
    {
      Yii::app()->user->setReturnUrl($this->getCurrentUrl());
    }

    parent::afterAction($action);
  }
}