<?php

$a = 50;
var_dump(eval ('if ($a>5 AND $a < 100) return true; else return false;'));
throw new Exception();