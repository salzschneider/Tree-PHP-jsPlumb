Tree-PHP-jsPlumb
================

A little fun with jsPlumb and my self made minimal-minimal PHP MVC framework.


Installing
----------------
- download the project
- wwwroot folder  must point to the `web` folder of the project
- run `config/tree.sql` in your database
- create a copy from `config/db_example.json` in `config/` folder named `db.json`
- set the database connection in `config/db.json`
- done

Features
----------------
- `+`: add new node to the parent node
- `-`: delete a whole branch - except root node
- `c`: collapse a branch 
- `e`: expand a branch
- `double click on title`: rename the title of a the node - then `press enter`

Requirements
----------------
- PHP 5 >= 5.2.0
- MySQL
- mysqli extension
