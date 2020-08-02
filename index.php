<?php

set_time_limit(0);

require './classes/SpiderUdemy/ListaLinkCursosUdemy.php';
require './classes/SpiderUdemy/CursoUdemy.php';

$txtLinks = file_get_contents('storage/links_udemy.txt');
$links = unserialize($txtLinks);

$arrayCurso = [];

$txtLinksProcessados = file_get_contents("storage/links_processados_udemy.txt");
$linksProcessados = explode("\n", $txtLinksProcessados);

foreach ($links as $l) {
    if (!array_search($l ['link'], $linksProcessados)) {
        $cur = new CursoUdemy($l ['id'], $l ['link']);
        $curso = $cur->getCurso();
        print_r($curso);
        $linksProcessado = "\n".$l ['link'];
        file_put_contents("storage/links_processados_udemy.txt", $linksProcessado, FILE_APPEND);
        
        //Limpando a memória
        unset($curso);
        unset($cur);
    }
}

?>