<?php
/**
 *  General controller class
 */
class mvcController
{
    private $_path;
    private $_callParts;
    private $_call;
    
    public function __construct()
    {
        $this->parsePath();
    }
    
    /**
     * Parse the url
     */
    private function parsePath()
    {
        $path = array();
        if (isset($_SERVER['REQUEST_URI'])) 
        {
            $request_path = explode('?', $_SERVER['REQUEST_URI']);

            $path['base'] = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');
            $path['call_utf8'] = substr(urldecode($request_path[0]), strlen($path['base']) + 1);
            $path['call'] = utf8_decode($path['call_utf8']);
            if ($path['call'] == basename($_SERVER['PHP_SELF'])) 
            {
                $path['call'] = '';
            }

            $path['call_parts'] = explode('/', $path['call']);

            $path['query_utf8'] = urldecode($request_path[1]);
            $path['query'] = utf8_decode(urldecode($request_path[1]));
            $vars = explode('&', $path['query']);

            foreach ($vars as $var) 
            {
                  $t = explode('=', $var);
                  $path['query_vars'][$t[0]] = $t[1];
            }
        }
        
        $this->_path = $path;
        $this->_callParts = $path['call_parts'];
        $this->_call = $path['call'];
    }
    
    /**
     * Get the special array which represents the parsed url
     * 
     * @return array
     */
    public function getPath()
    {
        return $this->_path;
    }
    
    /**
     * Invoke the appropriate action  by url
     */
    public function invokeAction()
    {
        if(!empty($this->_callParts))
        {
            $treeAction = new treeAction();
            $is404 = true;
  
            //action name 
            if(!empty($this->_callParts[0]) && method_exists($treeAction, 'execute'.ucfirst($this->_callParts[0])))
            {      
                $is404 = false;
                $actionName = 'execute'.ucfirst($this->_callParts[0]);
                array_shift($this->_callParts);              
                $treeAction->$actionName($this->_callParts);
                
                if(($treeAction->getTemplateStatus() == mvcActions::ADD))
                {
                    $template = $treeAction->getActionProperties();
                    include_once '../app/view/layout.php';
                }
            } 
            
            //without any parameters and actions
            if(empty($this->_callParts[0]) && empty($this->_call))
            {
                $is404 = false;
                $actionName = 'executeIndex';           
                $treeAction->$actionName();
                
                if(($treeAction->getTemplateStatus() == mvcActions::ADD))
                {
                    $template = $treeAction->getActionProperties();
                    include_once '../app/view/layout.php';
                }
            }
            
            //invoke the 404 page
            if($is404)
            {
                $this->invoke404();
            }
        }
    }
    
    /**
     * invoke the 404 page
     */
    private function invoke404()
    {
        include_once '../app/view/404.php';
    }
}
?>