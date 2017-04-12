<?php
namespace RForge\Exception;

use Exception;
class SystemException extends Exception
{
    
    public function present(){
        $msg = $this->message;
        echo $msg;
        return $msg;
    }
   
}

?>