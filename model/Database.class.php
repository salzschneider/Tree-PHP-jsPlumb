<?php
/**
 * Database connection handler - singelton
 */
class Database
{
    /**
     * Database connection resource
     * 
     * @var mysqli 
     */
    private static $_dbConnection;
    
    /**
     * Database configuration object
     * 
     * @var DbConfig 
     */
    private static $_connectionConfig;
    
    /**
     * Get or create and get a database connection resource
     * 
     * @param DbConfig $connectionConfig
     * @return mysqli
     */
    public static function getDbConnectionInstance(DbConfig $connectionConfig = null)
    {
        if(is_null(self::$_dbConnection))
        {
            if(is_null($connectionConfig))
            {
                $dbConfig = new DbConfig();
                $dbConfig->setConfigValuesFromConfig();
            }
                
            self::createConnection($dbConfig);
            return self::$_dbConnection;           
        }
        else
        {
            return self::$_dbConnection;
        }
    }
    
    /**
     * Create and set a database connection resource
     * 
     * @param DbConfig $connectionConfig
     * @throws DatabaseException
     */
    private static function createConnection(DbConfig $connectionConfig)
    {
        self::$_connectionConfig = $connectionConfig;
        
        $mysqli = @new mysqli(self::$_connectionConfig->host, 
                              self::$_connectionConfig->user, 
                              self::$_connectionConfig->password, 
                              self::$_connectionConfig->database);

        if ($mysqli->connect_errno) 
        {
            throw new DatabaseException("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        } 
        
        if (!$mysqli->set_charset("utf8")) 
        {
            throw new DatabaseException("Error loading character set utf8: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        } 
        
        self::$_dbConnection = $mysqli;
    }
}

?>