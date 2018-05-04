<?php
namespace Framework;
use\GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Router;
class App {
  /**
   * list of modules
   * @var array
   */
  private $modules = [];

  /**
   * router
   * @var Router
   */
   private $router;

  /**
   * App __construct
   * @param string[] $modules liste des module à charger
   */
  public function __construct(array $modules,array $dependencies =[])
  {
    $this->router = new Router;
    if(array_key_exists('renderer', $dependencies)){
        $dependencies['renderer']->addGlobal('router', $this->router);
    }
    foreach($modules as $module){
      $this->modules[] = new $module($this->router, $dependencies['renderer']);
    }
  }

  public function run(ServerRequestInterface $request):ResponseInterface{
    $uri = $request->getUri()->getPath();


   if (!empty($uri) && $uri[-1] === "/"){
      return (new Response())
      ->withStatus(301)
      ->withHeader('location',substr($uri,0,-1));
    }
    $route =$this->router->match($request);
    if (is_null($route)) {
      return new Response(404,[],'<h1>Erreur 404</h1>');
    }
    $params = $route->getParams();
    $request = array_reduce(array_keys($params),function($request, $key) use ($params){
      return $request->withAttribute($key,$params[$key]);
    }, $request);
    $response = call_user_func_array($route->getCallback(), [$request]);
    if (is_string($response)) {
      return new Response(200, [], $response);

    }elseif($response instanceof ResponseInterface){
      return $response;

    }else{
      throw new \Exception('the response is not a string or an instance of ResponseInterface ');
    }

  }

}
