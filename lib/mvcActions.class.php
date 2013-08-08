<?php
/**
 * General parent action class
 */
class mvcActions
{
    const NONE = 0;
    const ADD = 1;
    
    /**
     * If we wanna use layout
     * 
     * @var integer 
     */
    protected $template = self::ADD;
    
    /**
     * List of variables and data structures what we want to pass to layout
     * 
     * @var array 
     */
    protected $actionProperties = array();

    /**
     * Get the template status
     * 
     * @return integer
     */
    public function getTemplateStatus()
    {
        return $this->template;
    }
    
    public function __set($name, $value)
    {
        $this->actionProperties[$name] = $value;
    }
    
    /**
     * Get the list of properties
     * 
     * @return array
     */
    public function getActionProperties()
    {
        return $this->actionProperties;
    }
}
?>