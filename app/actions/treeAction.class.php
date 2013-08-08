<?php
/**
 *  Action handler and list 
 */
class treeAction extends mvcActions
{
    public function executeIndex()
    {
        $treeObject = new Tree();
        $this->treeJson = $treeObject->getTreeJson();
    }
    
    /**
     * Ajax request - add a new node
     * 
     * @param array $params
     * return id of the new node
     */
    public function executeAdd($params)
    {         
        $parentId = $params[0];
        $treeObject = new Tree();
        $newNodeId = $treeObject->addNode($parentId);
        
        $this->template = self::NONE;
        echo $newNodeId;
    }
    
    /**
     * Ajax request - delete the tree or a subtree
     * Return the deleted branch 
     * 
     * @param array $params
     * return array 
     */
    public function executeDelete($params)
    {               
        $id = $params[0];
        $treeObject = new Tree();
        $branchArray = $treeObject->getTreeArray($id);      
        $isSuccess = $treeObject->deleteBranch($id);
        
        $this->template = self::NONE;
        
        $returnArray = array("isSuccess"   => $isSuccess,
                            "branchArray" => $branchArray);
        
        $returnJson = json_encode($returnArray);
        
        echo $returnJson;
    }
    
    /**
     * Ajax request - rename a special node
     * 
     * return string
     */
    public function executeRename()
    {
        $treeObject = new Tree();
        $isSuccess = $treeObject->updateTitle($_POST['id'], $_POST['title']);
        
        $this->template = self::NONE;
        echo $isSuccess;
    }
}
?>