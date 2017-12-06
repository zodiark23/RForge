<?php
namespace RForge\Exception;

use Exception;
class SystemException extends Exception
{
    protected $type = "SystemException";
    
    public function present(){
        $msg = $this->message;
        $body= "<pre style='margin:0 auto;width:80%;color:white;background-color:#696969;padding:10px'>";
        $body.= "<h3>RForge Error : ".$this->type."</h3><br>";
        $body.= "<span>".$msg."</span><br>";
        $body.= "<div style='margin-top:5px;'>".$this->getTraceAsString()."</div style='margin-top:5px;'>";
        $body .="<br></br>";
        $body .="Exited with code 1";
        $body.= "</pre>";
        
        echo $body;
        return $body;
    }
   
}

?>