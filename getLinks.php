<?php

require './classes/SpiderUdemy/ListaLinkCursosUdemy.php';

$linksArray = [];
$linksGeral = [];
for ($index = 1; $index < 30; $index++) {
    $lnk = new ListaLinkCursosUdemy(288, $index);
    $links = $lnk->obterLinks();
    
    foreach ($links as $l) {
        $linksArray [] = $l;
        $linksGeral [] = $l ['link'];
    }
    
}

$txtLinks = serialize($linksArray);
file_put_contents("storage/links_udemy.txt", $txtLinks);

$txt = implode("\n", $linksGeral);
file_put_contents("storage/links_geral_udemy.txt", $txt);

?>