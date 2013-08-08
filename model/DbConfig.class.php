<?php
/**
 * Database configuration handler
 */
class DbConfig
{
    private $_configArray = array();
    
    /**
     * Set the database configuration values from parameters.
     * 
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $dbName
     */
    public function setConfigValues($host, $user, $password, $dbName)
    {
        $this->_configArray['host'] = $host;
        $this->_configArray['database'] = $dbName;
        $this->_configArray['user'] = $user;
        $this->_configArray['password'] = $password;
    }
    
    /**
     * Set the database configuration values from db.json.
     * 
     * @throws DatabaseException
     */
    public function setConfigValuesFromConfig()
    {
        $config = file_get_contents('../config/db.json');
        $this->_configArray = json_decode($config, true);
        
        if(empty($this->_configArray))
        {
            throw new DatabaseException("Invalid db.json");
        }
    }          

    /**
     * Get magic method to get the db parameters
     * 
     * @param string $name
     * @return array
     * @throws DatabaseException
     */
    public function __get($name)
    {
        $configArray = array();
        if(isset($this->_configArray[$name]))
        {
           $configArray =  $this->_configArray[$name];
        }
        else
        {
            throw new DatabaseException("Invalid property name: ".$name);
        }
       
        return $configArray;
    }
    
    /**
     * Get configuration parameters in array
     * 
     * @return array
     */
    public function getConfigArray()
    {
        return $this->_configArray;
    }
}
?>