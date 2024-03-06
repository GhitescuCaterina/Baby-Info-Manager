<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

// declare(strict_types=1);

class Router
{
    // a Router class that takes a URI and determines which controller and method to call
    // the Router class will be called from the init.php file in the public folder

    // properties for the controller, method, and parameters for the request
    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];

    // constructor function, automatically called when a new instance of Router is created
    public function __construct()
    {
        echo "Router was created.\n";
        //echo "here" . $_SERVER['REQUEST_URI'] . "\n";

        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        $url = $protocol . '://' . $host . $uri;
        $url = parse_url($url);

        $url = explode('/', filter_var(rtrim(substr($url['path'], 1), '/'), FILTER_SANITIZE_URL));
        //echo "url: ";
        //print_r($url);

        if (file_exists('../src/controller/' . $url[2] . '.php')) {
            // if the controller exists, set it as the controller
            $this->controller = $url[2];
            //echo "controller: " . $this->controller . "\n";
            unset($url[2]);
        }

        require_once '../src/controller/' . $this->controller . '.php';
        // instantiate the controller
        $this->controller = new $this->controller;

        if (isset($url[3])) {
            // if the method exists, set it as the method
            if (method_exists($this->controller, $url[3])) {
                $this->method = $url[3];
                unset($url[3]);
            } else {
                // if the method doesn't exist, return an error
                echo "Method does not exist";
            }
        }

        // if there are any parameters left in the url, set them as the parameters
        $this->params = $url ? array_values($url) : [];

        // call the method on the controller and pass in the parameters
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
}