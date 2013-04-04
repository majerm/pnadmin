<?php

$container = require __DIR__ . '/../bootstrap.php';

$x = new \PNAdmin\AdminUserRow;

var_dump(is_subclass_of('\PNAdmin\AdminUserRow', '\Nette\Database\Table\ActiveRow'));
