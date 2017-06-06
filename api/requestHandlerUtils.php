<?php
include('Request.php');

/**
 * Request Handler Utils
 *
 * @author Rylan Gotto <rgotto2@gmail.com>
 */

/**
 * Determines if controller file exists and lazy loads the controller to handle the request
 * For example if /jsonator/api.php/user was requested, UserController would have to exist to statisfy the request
 */
function handleRequest(){
    $request = new Request();
    $controller_name = ucfirst($request->path_info[0] . 'Controller');

    if (apiAutoload($controller_name)) { 
        $controller = new $controller_name($request);
    }else{
        graceful404();
    }
    return true;
}
/**
 * Determines if controller file exists and lazy loads the controller to handle the request
 *
 * @param String $file_name The file name of the controller to lazy load
 */
function apiAutoload($file_name)
{
    if (preg_match('/[a-zA-Z]+Controller$/i', $file_name)) {
        $file_path = realpath(__DIR__ . '/..') . '/controllers/' . $file_name . '.php';
        if (file_exists ($file_path)){
            include $file_path;
            return true;
        }else{
            return false;
        }
    } 
}
/**
 * If something goes wrong send 404 with predefined string or custom string
 *
 * @param String $content The message to deliever in response if request could not be preocessed
 */
function graceful404($content=null){
    header('HTTP/1.0 404 Not Found');
    echo "<h1>404 Not Found</h1>";
    echo ($content ? $content : "The page that you have requested could not be found.");
    exit();
}