<?php
$image_width = 600;
$image_height = 600;
$image = imageCreate($image_width, $image_height);
$bg_color = ImageColorAllocate($image, 255, 255, 255);
if(!isset($_GET['read']))
{
    $sect = rand(2, 10);
    $sum = 0;
    $mass = array();
    for($i = 0; $i < $sect; $i++)
    {
        array_push($mass, rand(1, 100));
        $sum = $sum + $mass[$i];
    }
    $old_loc = 0;
    for($i = 0; $i < $sect; $i++)
    {
        $color = ImageColorAllocate($image,rand(0,255),rand(0,255),rand(0,255));
        $new_loc = 360*$mass[$i]/$sum;
        ImageFilledArc($image, 300, 300, 300, 300, $old_loc, $old_loc + $new_loc, $color, IMG_ARC_PIE);
        $old_loc = $old_loc + $new_loc;
    }
    ImagePng($image, '1.png');
    echo"<img src = '1.png'>";
    echo"<form> <input type=submit name=build value=Построить> <input type=submit name=read value=Считать> <br> </form>";
}
else
{
    $contents = file("file.txt");
    $sect = 0;
    $sum = 0;
    $mass = array();
    $color_mass = array();
    foreach($contents as $line)
    {
        $str = explode(":", $line, 4);
        array_push($color_mass, ImageColorAllocate($image,intval($str[1]),intval($str[2]),intval($str[3])));
        $sect++;
        array_push($mass, intval($str[0]));
        $sum = $sum + $str[0];
    }
    $old_loc = 0;
    for($i = 0; $i < $sect; $i++)
    {
        $new_loc = 360*$mass[$i]/$sum;
        ImageFilledArc($image, 300, 300, 300, 300, $old_loc, $old_loc + $new_loc, $color_mass[$i], IMG_ARC_PIE);
        $old_loc = $old_loc + $new_loc;
    }
    ImagePng($image, '2.png');
    echo"<img src = '2.png'>";
    echo"<form> <input type=submit name=build value=Построить> <input type=submit name=read value=Считать> <br> </form>";
}
ImageDestroy($image);
?>