<?php
    $data = file_get_contents('php://input');
    $data = "./files/" . substr($data, 1);
    $filecontent = file_get_contents($data);
    echo $filecontent;
?>