<?php 
echo "<br />".$this->pageTitle;
    echo "<pre>";print_r($this->userdata); echo "</pre>";
    echo "<pre>";print_r($this->userTool); echo "</pre>";
    
    function showUserData($obj) {
        
        echo "<br />".$obj->pageTitle;
        echo "<pre>";print_r($obj->userdata); echo "</pre>";
        echo "<pre>";print_r($obj->userTool); echo "</pre>";
        
    }
    
    showUserData($this);