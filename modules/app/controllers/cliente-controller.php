<?php

class clienteController extends MainController {

    public function index(){
        $vw = new view('cliente/pedro');
        $vw->variavel = "pedro é bixa";
        $vw->block('bpedro');
        $vw->variavel = "o paulo tb";
        $vw->block('bpedro');
        $vw->variavel = "o fabio nao";
        $vw->block('bpedro');
        $vw->show();
    }

}
