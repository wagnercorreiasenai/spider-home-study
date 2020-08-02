<?php

class ListaLinkCursosUdemy {

    private $url;
    private $categoria;
    private $pagina;
    private $urlBase;

    public function __construct($categoria = 288, $pagina = 1) {
        $this->categoria = $categoria;
        $this->pagina = $pagina;
        $this->url = "https://www.udemy.com/api-2.0/discovery-units/all_courses/?p=" . $this->pagina . "&page_size=16&subcategory=&instructional_level=&lang=&price=&duration=&closed_captions=&category_id=" . $this->categoria . "&source_page=category_page&locale=pt_BR&currency=brl&navigation_locale=en_US&skip_price=true&sos=pc&fl=cat";
        $this->urlBase = "https://www.udemy.com";
    }

    private function setBrowser() {
        $fake_user_agent = "Mozilla/5.0 (X11; Linux i686) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11";
        ini_set('user_agent', $fake_user_agent);
    }

    public function obterLinks() {

        $json = file_get_contents($this->url);
        $obj = json_decode($json);

        $links = [];

        //Percorre os cursos
        $items = $obj->unit->items;
        foreach ($items as $item) {
            $link = $this->urlBase . $item->url;
            $id = $item->id;
            
            $linkCompleto ['link'] = $link;
            $linkCompleto ['id'] = $id;
            
            $links [] = $linkCompleto;
        }

        return $links;
    }

}
