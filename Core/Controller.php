<?php

namespace Core;

/**
 * Base controller
 *
 * PHP version 7.3
 */
abstract class Controller
{
    /**
     * Parameters from the matched route
     * @var array
     */
    protected $route_params = [];

    protected $servicesNamespace;

    protected $jsonMapper;

    protected $result;

    protected $request;
    /**
     * Class constructor
     *
     * @param array $route_params  Parameters from the route
     *
     * @return void
     */
    public function __construct($route_params,
                                \Interfaces\Helpers\JsonMapperInterface $jsonMapper,
                                \Interfaces\Services\ServiceResultInterface $result
                                )
    {
        $this->route_params = $route_params;

        $this->jsonMapper = $jsonMapper;

        $this->result = $result;

        $this->servicesNamespace = "\\App\\Models";
    }

    /**
     * Magic method called when a non-existent or inaccessible method is
     * called on an object of this class. Used to execute before and after
     * filter methods on action methods. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     *
     * @param string $name  Method name
     * @param array $args Arguments passed to the method
     *
     * @return void
     */
    public function __call($name, $args)
    {
        if(isset($_REQUEST['data'])) {
            $data = $_REQUEST['data'];
        } else {
            $putData = fopen("php://input", "r");
            $data = "";
            while($additionalData = fread($putData, 1024))
                $data .= $additionalData;
            fclose($putData);
        }

        if(empty($data)) {
            $data = "{}";
        }

        $method = $name . 'Action';

        if (method_exists($this, $method)) {
                if($this->before($_SERVER['REQUEST_METHOD'], $data) !== FALSE) {

                    if (isset($this->route_params['id'])) {
                        $args = [
                            'id' => $this->route_params['id']
                        ];
                    }

                    call_user_func_array([$this, $method], array($this->request, $this->result));

                }

        } else {
            $this->result->addError("Method $method not found in controller " . get_class($this));
        }

        $result = $this->result->serialize();

        $this->after($result);
    }

    /**
     * Before filter - called before an action method.
     *
     * @return void
     */
    protected function before($requestType, $jsonData)
    {
        list($command, $function)= $this->extractServiceName($_SERVER['REQUEST_URI']);

        $requestType = ucfirst(strtolower($requestType));

        $classNameRequest = $this->servicesNamespace."\\".ucfirst($command)."\\Request\\"."$requestType\\".$function;

        if(!class_exists($classNameRequest)){
            $this->result->addError("Unknown command");
        }else{

            $jsonObject = json_decode($jsonData);

            $dataObject = new $classNameRequest();

            if($jsonObject == FALSE) {
                $this->result->addError("Unable to deserialize request");
            }else{
                $dataObject = $this->jsonMapper->map($jsonObject, $dataObject);

                $validateClass = $this->servicesNamespace."\\".ucfirst($command)."\\user";
                $validateClass::validate($dataObject, $this->result);

                $this->request = $dataObject;
            }
         }
    }

    /**
     * After filter - called after an action method.
     *
     * @return void
     */
    protected function after($result)
    {

        header("Content-Type: application/json");

        echo $result;
    }

    function extractServiceName($requestUri)
    {
        if(strlen($requestUri) <= 5) // the request URI must contain "/api/" at least
        {
            return false;
        }

        $requestUri = substr($requestUri, 5); // remove "/api/"

        $pos = strpos($requestUri, "?");
        if($pos === false) {
            $pos = strpos($requestUri, "/");
        }
        if($pos === false) {
            // A service may not need any data. Therefore, we may not have ? nor / in the URI
            return $requestUri;
        }

        $serviceName = substr($requestUri, 0, $pos);

        $serviceFun = substr($requestUri, $pos + 1) . 'Action';

        $serviceName = ucfirst(substr($serviceName, 0, -1));

        return [$serviceName, $serviceFun];

    }
}
