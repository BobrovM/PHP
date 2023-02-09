<?php
    $arr = glob("./files/*.txt");
    $encarr = json_encode($arr);
    echo $encarr;
?>