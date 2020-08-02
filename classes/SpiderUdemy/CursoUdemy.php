<?php

require './classes/Curso.php';

class CursoUdemy {

    private $urlDadosCurso;
    private $urlCurso;
    private $urlDetalhesCurso;
    private $id;
    private $link;

    public function __construct($id = 2173004, $url = "https://www.udemy.com/course/desenvolvimento-android-e-ios-com-flutter/") {
        $this->link = $url;
        $this->id = $id;
        $this->urlCurso = "https://www.udemy.com/api-2.0/course-landing-components/" . $this->id . "/me/?components=deal_badge,discount_expiration,gift_this_course,price_text,purchase,redeem_coupon,slider_menu,cacheable_deal_badge,cacheable_discount_expiration,cacheable_price_text,cacheable_buy_button,buy_button,cacheable_purchase_text,cacheable_add_to_cart,money_back_guarantee";
        $this->urlDadosCurso = "https://www.udemy.com/api-2.0/course-landing-components/" . $this->id . "/me/?components=practice_test_bundle,recommendation,instructor_bio,instructor_links,curated_for_ufb_notice,top_companies_notice,incentives,featured_qa,caching_intent";
        $this->urlDetalhesCurso = "https://www.udemy.com/api-2.0/discovery-units/?context=landing-page&from=0&page_size=6&item_count=18&course_id=" . $this->id . "&source_page=course_landing_page&locale=pt_BR&currency=brl&navigation_locale=en_US&skip_price=true";
    }

    private function setBrowser() {

        $options = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                "Cookie: foo=bar\r\n" . // check function.stream-context-create on php.net
                "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
            )
        );

        $context = stream_context_create($options);

        return $context;
    }

    public function getCurso() {

        $curso = new Curso();
        $curso->idRemoto = $this->id;
        $curso->link = $this->link;
        $curso->dataAtualizacao = date('Y-m-d H:i:s');

        //Meta dados do curso
        $jsonCurso = file_get_contents($this->urlCurso, false, $this->setBrowser());
        $objCurso = json_decode($jsonCurso);

        $curso->nome = $objCurso->slider_menu->data->title;
        $curso->distribuidora = "Udemy";
        $curso->classificacao = $objCurso->slider_menu->data->rating;
        $curso->preco = $objCurso->buy_button->button->payment_data->purchasePrice->amount;

        //Dados específicos do curso
        $jsonDadosCurso = file_get_contents($this->urlDadosCurso, false, $this->setBrowser());
        $objDadosCurso = json_decode($jsonDadosCurso);

        $curso->certificado = $objDadosCurso->incentives->has_certificate;
        $cargaHoraria = $objDadosCurso->incentives->video_content_length;
        $cargaHoraria = str_replace(",", ".", $cargaHoraria);
        $curso->cargaHoraria = str_replace(" hours", "", $cargaHoraria);

        //Detalhes do curso
        $jsonDetalhesCurso = file_get_contents($this->urlDetalhesCurso, false, $this->setBrowser());
        $objDetalhesCurso = json_decode($jsonDetalhesCurso);
        $curso->resumo = $objDetalhesCurso->units[0]->items[0]->headline;

        return $curso;
    }

}
