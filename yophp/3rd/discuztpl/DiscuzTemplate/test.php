<?php



if (eregi ("\php", "PHP is  web scripting language of choice.")) {
   print "A match was found.";
} else {
   print "A match was not found.";
}

$expr = "<a href=\"http://www.sina.com\" >sina</a>";

$expr = ereg_replace("/<a href=\"http://[.a-z]*\">/i", "G", $expr);

echo $expr;
?>