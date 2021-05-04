<?php

/* 
 * Criaçao das Rotas no formato url => modulo/controller/funcao
 */
$rotas = array(
    '/'=>'app/codigo/create',
    'login'=>'app/usuario/login',
    'forgetpass'=>'app/usuario/forgetpass',
    'teste'=>array(
        'teste1'=>'app/usuario/grid',
        'teste2'=>'app/menu/form'
    )
    
);
