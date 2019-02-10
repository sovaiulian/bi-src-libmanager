<?php
$content="hello world, ";
$name = $this->html->readRQ('name');
$date = $this->html->readRQd('date',8);
$content.= "$name $date";
$out.=$this->html->tag($content,'h1','class');
echo $out;
//<h1>test</h1>;
//<div><p>  </p></div>