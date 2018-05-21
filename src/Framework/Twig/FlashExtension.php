<?php
namespace Framework\Twig;
use \Framework\Session\FlashService;
/**
 * serie d'extention concernant les message flash
 */
class FlashExtension extends \Twig_Extension {

  /**
  * @var FlashService
  */
  private $flashService;

  public function __construct(FlashService $flashService){

    $this->flashService = $flashService;
  }

/**
 * @return \Twig_SimpleFunction[]
 */
  public function getFunctions(): array
  {
    return[
    new \Twig_SimpleFunction('flash',[$this,'getFlash'])
    ];
  }


  public function getFlash($type): ?string
  {

    return $this->flashService->get($type);

  }
}
