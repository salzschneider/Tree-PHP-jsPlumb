/**
 * Node representation 
 * 
 * @param {integer} id
 * @returns {Node}
 */
Node = function Node (id) 
{
    var that = this;
    this.id = id;
    this.parentId = 0;
    this.childrenIds = [];
    
    this.title = "";
    
    this.orderNumber = 0;
    this.levelWidth = 1;
    
    this.top = 100;
    this.left = 500;
    this.width = 100;
    this.inputBox = $('<input type="text" val="">');
    
    //Node class constructor
    constructor();
    
    this.getId = function()
    {
        return this.id;
    };
    
    this.setParentId = function(id)
    {
        this.parentId = parseInt(id);
    };
    
    this.getParentId = function()
    {
        return this.parentId;
    };
    
    //
    this.hasParent = function()
    {
        var hasParent = this.parentId !== 0 ? true : false;
        return hasParent;
    };
    
    this.addChildrenId = function(id)
    {
        this.childrenIds[id] = id;
    };
    
    this.removeChildrenId = function(id)
    {
        delete this.childrenIds[id];
    };
    
    this.getChildrenIds = function()
    {
        return this.childrenIds;
    };
    
    this.purgeChildrenIds = function()
    {
        this.childrenIds = [];
    };
    
    function constructor()
    {
        createNodeToBoard();
        
        $("#title" + that.id ).dblclick(function()
        {
            that.inputBox.val($("#title" + that.id ).text());  
            $("#title" + that.id ).hide(); 

            that.inputBox.show();
            that.inputBox.focus();
        }); 
        
        that.inputBox.keypress(function(e) 
        {
            if(e.which == 13)
            {
                sendNewTitleAjax(that.id, that.inputBox.val());
            }
        });
        
        $("#add" + that.id ).click(function()
        {
            Tree.addNode(that.id);
        });
        
        $("#del" + that.id ).click(function()
        {
            Tree.deleteBranch(that.id);
        });
        
        $("#col" + that.id ).click(function()
        {
            Tree.collapseBranch(that.id);
        });
        
        $("#exp" + that.id ).click(function()
        {
            Tree.expandBranch(that.id);
        });
    };
    
    function sendNewTitleAjax(id, title)
    {
        var request = $.ajax({
          url: "/rename/",
          type: "POST",
          data: {id : id, title: title},
          cache: false,
          dataType: "html"
        });
        
        request.done(function(success) 
        {
            if(parseInt(success))
            {
                that.inputBox.hide();
                $("#title" + that.id ).text(that.inputBox.val());
                $("#title" + that.id ).show(); 
            }
        });

        request.fail(function(jqXHR, textStatus) 
        {
            alert( "Request failed: " + textStatus );
        });
    }
    
    this.getPosition = function ()
    {
        var position = {top: this.top, left: this.left};
        return position;
    };
    
    this.setPositionByParent = function (parentPosition)
    {
        this.top = parentPosition.top + 150;
        this.left = parentPosition.left - ((this.levelWidth - 1) * 100) + this.orderNumber * 200;
    };
    
    this.setOrderNumber = function(orderNumber)
    {
        this.orderNumber = parseInt(orderNumber);
    };
    
    this.setLevelWidth = function(levelWidth)
    {
        this.levelWidth = parseInt(levelWidth);
    };
    
    function createNodeToBoard()
    {
        $("<div id=\"node" + that.id + "\" class=\"item\"></div>").appendTo("#treeBoard");  
        $("#node" + that.id).append("<div class=\"add\" id=\"add" + that.id + "\" title=\"Add node\">+</div>");
        $("#node" + that.id).append("<div class=\"del\" id=\"del" + that.id + "\" title=\"Delete branch\">-</div>");
        $("#node" + that.id).append("<div class=\"col\" id=\"col" + that.id + "\" title=\"Collapse branch\">C</div>");
        $("#node" + that.id).append("<div class=\"exp\" id=\"exp" + that.id + "\" title=\"Expand branch\">E</div>");
        $("#node" + that.id).append("<h3 class=\"title\" id=\"title" + that.id + "\"></h3>");
        that.inputBox.hide();
        $("#node" + that.id).append(that.inputBox);
        $("#node" + that.id).css({top: that.top, left: that.left, position:'absolute'});     

        //node is draggable
        jsPlumb.draggable($("#node" + that.id));
    };
    
    this.destruct = function ()
    {
        $("#node" + that.id).remove();
    };
    
    this.redrawNode = function()
    {
        $("#node" + that.id).css({top: that.top, left: that.left, position:'absolute'}); 
    };
    
    this.setTitle = function(title)
    {
        this.title = title;
        $("#title" + that.id).text(title);
    };
    
    this.hide = function()
    {
        $("#node" + that.id).hide();
    };
    
    this.show = function()
    {
        $("#node" + that.id).show();
    };
};
