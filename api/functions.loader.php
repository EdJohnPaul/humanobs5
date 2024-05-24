<?php
foreach (glob("functions/*.php") as $filename)
{
    include $filename;
	//echo $filename."</br>";
}