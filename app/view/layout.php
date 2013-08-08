<!DOCTYPE html>
<html>
    <head>
        <title>Tree-PHP-jsPlumb</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="/css/ui-lightness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
        <link href="/css/tree.css" rel="stylesheet">
        <script type="text/javascript" src="/js/lib/jquery-1.9.0-min.js"></script>
        <script type="text/javascript" src="/js/lib/jquery-ui-1.9.2-min.js"></script>
        <script type="text/javascript" src="/js/lib/jquery.jsPlumb-1.4.1-all.js"></script>
        <script>
           var jsonTree = '<?php echo $template['treeJson']?> ';  
           var tree = $.parseJSON(jsonTree);
        </script> 
        
    </head>
    <body>
        <div id="header">Tree-PHP-jsPlumb</div>
        <div id="treeBoard"></div>
        <script type="text/javascript" src="/js/node.js"></script> 
        <script type="text/javascript" src="/js/tree.js"></script>
    </body>
</html>
