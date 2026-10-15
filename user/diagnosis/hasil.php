<?php
require_once __DIR__.'/../../includes/user-check.php';
$id=(int)($_GET['id']??0);
redirect('/user/hasil-diagnosis.php?id='.$id);
