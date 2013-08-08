<?php
/**
 * Tree representation 
 */
class Tree
{
    /**
     * Database connection
     * 
     * @var mysqli resource
     */
    private $_databaseConnection;
    
    /**
     * Set the database connection
     */
    public function __construct()
    {
        $this->_databaseConnection = Database::getDbConnectionInstance();
    } 
    
    /**
     * Create the whole tree or a subtree (branch)
     * 
     * @param array $elements The whole tree or a branch
     * @param integer $parentId
     * @return array
     */
    private function createTreeArray(array $elements, $parentId = 0) 
    {     
        $branch = array();

        foreach ($elements as $element) 
        {
            if ($element['parent_id'] == $parentId) 
            {
                $children = $this->createTreeArray($elements, $element['id']);
                if ($children) 
                {
                    $element['children'] = $children;
                }
                
                $branch[] = $element;
            }
        }

        return $branch;
    }
    
    /**
     * Rename a node
     * 
     * @param integer $id Node id
     * @param string $newTitle
     * @return boolen
     */
    public function updateTitle($id, $newTitle)
    {
        $isSuccess = false;
        
        if ($stmt = $this->_databaseConnection->prepare("UPDATE tree set title = ? WHERE id = ?")) 
        {   
            $stmt->bind_param("si", $newTitle, $id);
            $isSuccess = $stmt->execute();
        }
    
        return $isSuccess;
    }
    
    /**
     * Search a node
     * 
     * @param integer $id Node id
     * @return boolean
     */
    private function isNodeExist($id)
    {
        $isExist = false;
        
        if ($stmt = $this->_databaseConnection->prepare("SELECT count(*) FROM tree WHERE id = ?")) 
        {   
            $stmt->bind_param("i",  $id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();          
            $isExist = (boolean)$count;
        }
        
        return $isExist;
    }
    
    /**
     * Add a node to the parent node
     * 
     * @param integer $parentId Node id
     * @return integet new node id
     */
    public function addNode($parentId)
    {
        $newNodeId = 0;  
        $isExistParent = $this->isNodeExist($parentId);
        
        if ($isExistParent && ($stmt = $this->_databaseConnection->prepare("INSERT INTO tree (parent_id, title) VALUES (?,?)"))) 
        {   
            $newTitle = "Parent: ".$parentId;
            $stmt->bind_param("is", $parentId, $newTitle);
            $stmt->execute();
            $newNodeId = $this->_databaseConnection->insert_id;           
        }
    
        return $newNodeId;
    }
    
    /**
     * Delete a subtree
     * 
     * @param integer $id Tree or subtree(branch) top node id
     * @return boolean
     */
    public function deleteBranch($id)
    {
        $isNodeExist = $this->isNodeExist($id);
        $isSuccess = false;
        
        if($isNodeExist)
        {
            $this->_databaseConnection->autocommit(FALSE);
            
            //keep the root node
            if($id != 1)
            {
                $this->deleteNode($id);
            }
            $branch = $this->getTreeArray($id);
            $this->deleteIteration($branch);
            
            $this->_databaseConnection->commit();
            $isSuccess = true;
        }
        
        return $isSuccess;
    }
    
    /**
     * Tree or subtree traversal and delete the nodes
     * 
     * @param type $branch
     */
    private function deleteIteration($branch)
    {
        foreach ($branch as $subBranch)
        {
            if(!empty($subBranch['children']))
            {
                $this->deleteIteration($subBranch['children']);
            }
            
            $this->deleteNode($subBranch['id']);
        }
    }
    
    /**
     * Delete a special node
     * 
     * @param integer $id Node id
     * @return boolen
     */
    private function deleteNode($id)
    {
        $isSuccess = false;
        
        if ($stmt = $this->_databaseConnection->prepare("DELETE FROM tree WHERE id = ?")) 
        {   
            $stmt->bind_param("i", $id);
            $isSuccess = $stmt->execute();
        }
        
        return $isSuccess;
    }
    
    /**
     * Get the tree or a branch in array
     * 
     * @param integer $id Node id
     * @return array
     */
    public function getTreeArray($id = 0)
    {
        $result = $this->_databaseConnection->query("SELECT id, title, parent_id FROM tree");
        $rows = $result->fetch_all(MYSQLI_ASSOC);       
        $tree = $this->createTreeArray($rows, $id);
        
        return $tree;
    }

    /**
     * Get the tree or a branch in json
     * 
     * @param integer $id Node id
     * @return string
     */
    public function getTreeJson($id = 0)
    {
        $tree = $this->getTreeArray($id);
        $jsonTree = json_encode($tree);

        return $jsonTree;
    }
}
?>