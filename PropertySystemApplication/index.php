<?php

include_once "routes.php";








function __autoload($classname) {
    if(file_exists('./controller/'.$classname.'.php')) {
        require_once './controller/'.$classname.'.php';
    } else if (file_exists('./database/'.$classname.'.php')) {
        require_once './database/'.$classname.'.php';
    }else if (file_exists('./model/'.$classname.'.php')) {
        require_once './model/'.$classname.'.php';
    }else if (file_exists('./router/'.$classname.'.php')) {
        require_once './router/'.$classname.'.php';
    }
}
