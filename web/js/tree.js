/**
 * Tree representation 
 * @type type
 */
var Tree = 
{ 
    nodes : [],
            
    common : {
        paintStyle:{ lineWidth:1, strokeStyle:"black" },
        anchors:[ "BottomCenter", "TopCenter" ],
        deleteEndpointsOnDetach:false,
        endpoint:"Blank",
        connector:"Straight",
        ConnectionsDetachable:false
    },
            
    init : function()
    {
        jsPlumb.ready(function() 
        {
            Tree.buildTree(tree, 0);
        });
    },
    
    buildTree : function (subTree, depth)
    {
        var orderNumber = 0;
        var levelWidth = subTree.length;

        for (var x in subTree)
        {     
            var newNode = new Node(subTree[x].id);
            Tree.nodes[subTree[x].id] = newNode;

            newNode.setParentId(subTree[x].parent_id); 
            newNode.setTitle(subTree[x].title);  
            newNode.setOrderNumber(orderNumber);
            newNode.setLevelWidth(levelWidth);

            if(newNode.hasParent())
            {
                var parentPosition = Tree.nodes[newNode.getParentId()].getPosition();
                newNode.setPositionByParent(parentPosition);
                newNode.redrawNode();
                Tree.nodes[newNode.getParentId()].addChildrenId(newNode.getId());
            }

            if(subTree[x].children !== undefined)
            {
                Tree.buildTree(subTree[x].children, depth + 1);
            } 

            //connect node to parent
            if(newNode.hasParent())
            {
                jsPlumb.connect({ source:"node" + newNode.getParentId() , target:"node" + newNode.getId()}, Tree.common);
            }

            orderNumber++;
        }
    },
            
    addNode : function(parentId)
    {
        var request = $.ajax({
          url: "/add/"+parentId,
          type: "GET",
          cache: false,
          dataType: "html"
        });
        
        request.done(function(newNodeId) 
        {
            newNodeId = parseInt(newNodeId);
            parentId = parseInt(parentId);
            if(newNodeId)
            { 
                var newNode = new Node(newNodeId);
                Tree.nodes[newNodeId] = newNode;
                Tree.nodes[parentId].addChildrenId(newNodeId);
                
                newNode.setParentId(parentId); 
                newNode.setTitle("Parent: " + parentId);  

                var parentPosition = Tree.nodes[newNode.getParentId()].getPosition();
                parentPosition.left = parentPosition.left + Math.floor(Math.random() * 10);
                newNode.setPositionByParent(parentPosition);
                newNode.redrawNode();
                
                jsPlumb.connect({ source:"node" + newNode.getParentId() , target:"node" + newNode.getId()}, Tree.common);
            }
        });

        request.fail(function(jqXHR, textStatus) 
        {
            alert( "Request failed: " + textStatus );
        });
    },  
            
    deleteBranch : function(id)
    {
        var request = $.ajax({
          url: "/delete/" + id,
          type: "GET",
          cache: false,
          dataType: "json"
        });
        
        request.done(function(returnJson) 
        {
            if(returnJson.isSuccess)
            { 
                id = parseInt(id);
                
                //keep root node
                if(id !== 1)
                {
                    var parentId = Tree.nodes[id].getParentId();
                    Tree.nodes[parentId].removeChildrenId(id);
                    Tree.deleteNode(id);     
                } 
                else
                {
                    Tree.nodes[id].purgeChildrenIds();
                }
                
                Tree.deleteIteration(returnJson.branchArray);
            }
        });

        request.fail(function(jqXHR, textStatus) 
        {
            alert( "Request failed: " + textStatus );
        });
    },
         
    deleteIteration : function(branch)
    {
        for(var subBranch in branch)
        {    
            if(branch[subBranch].children !== undefined)
            {
                Tree.deleteIteration(branch[subBranch].children);
            }
            
            Tree.deleteNode(branch[subBranch].id);
        }
    },   
            
    deleteNode : function(id)
    {
        jsPlumb.detachAllConnections("node" + id);
        Tree.nodes[id].destruct();
        delete Tree.nodes[id];
    },            
    
    collapseBranch : function(id)        
    {
        this.collaspeItreation(id, id);      
    },
            
    collaspeItreation : function(id, topId)       
    {
        if(id !== topId)
        {    
            this.collapseNode(id);
        }
        
        var childrenIds = Tree.nodes[id].getChildrenIds();
           
        if(childrenIds.length > 0)
        {
            for(var child in childrenIds)
            {
                this.collaspeItreation(child, topId);
            }
        }     
    },
            
    collapseNode : function(id)
    { 
        jsPlumb.hide("node" + id, true);
        Tree.nodes[id].hide();
        
    }, 
            
    expandBranch : function(id)        
    {
        this.expandItreation(id, id);
    },
    
    expandItreation : function(id, topId)       
    {
        if(id !== topId)
        {    
            this.expandNode(id);
        }
        
        var childrenIds = Tree.nodes[id].getChildrenIds();
           
        if(childrenIds.length > 0)
        {
            for(var child in childrenIds)
            {
                this.expandItreation(child, topId);
            }
        }     
    },        
            
    expandNode : function(id)
    {
        jsPlumb.show("node" + id);
        Tree.nodes[id].show();
    }     
};

Tree.init();
