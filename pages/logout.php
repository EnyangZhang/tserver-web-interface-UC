<?php
    require_once(__DIR__."/../phpObj/Bootstrap3.php");

    session_start();    
    session_unset(); // remove all session variables    
    session_destroy(); // destroy the session
    
    Bootstrap3::createAlert("You have logged out. See you later!", "../index.php");
?>