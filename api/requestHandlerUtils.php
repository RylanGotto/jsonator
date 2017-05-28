<?php
include('Request.php');

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
function graceful404($content=null){
    header('HTTP/1.0 404 Not Found');
    echo "<h1>404 Not Found</h1>";
    echo ($content ? $content : "The page that you have requested could not be found.");
    exit();
}