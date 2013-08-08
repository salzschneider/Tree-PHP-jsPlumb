<?php
error_reporting(E_ALL ^ E_NOTICE);

function __autoload($class_name) 
{
    $directories = array(dirname(__FILE__).'/../model/',
                         dirname(__FILE__).'/../lib/',
                         dirname(__FILE__).'/../app/actions/');
    
    foreach($directories as $directory)
    {
        $file = $directory.$class_name . '.class.php';
        if (is_file($file))
        {
            include $file;
            return;
        }
    }
}

$controller = new mvcController();
$controller->invokeAction();
?>

