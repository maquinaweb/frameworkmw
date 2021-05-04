<?php

$tpl = new \mwwork\view("app/painelCMS/index");
$tpl->addFile("head", "app/painelCMS/head");
$tpl->addFile("header", "app/painelCMS/header");
$tpl->addFile("sidebar", "app/painelCMS/sidebar");
$tpl->addFile("script", "app/painelCMS/script");
if (isset($_SESSION['app'])) {
    $tpl->userphoto = $_SESSION['app']['userphoto'];
    $tpl->username = $_SESSION['app']['nome'];
}
$tpl->title = 'Framework MW';
$tpl->system_version = SYS_VERSION;
//        $tpl->titulopagina = $this->titulopagina;
//       $tpl->descricao = $this->descricao;
$tpl->plugins = HOME_URI . '/var/plugins/';
$tpl->url = HOME_URI;
$url = '/' . get('path');
$left_menu_name = 'lateral_esquerdo';

$joingrupo = "JOIN app_grupomenu grupomenu ON (grupomenu.id = menu.grupomenu_id)";
$joinmodulo = "LEFT JOIN app_modulo modulo ON(menu.modulo_id = modulo.id)";
$joinpermissao = "LEFT JOIN app_permissao permissao ON(modulo.id = permissao.modulo_id)";

if (!CHECK_PERMISSION) {
    $menus = \app\menu::all(array(
        'from' => 'app_menu as menu',
        'select' => 'menu.*',
        'joins' => array($joingrupo, $joinmodulo),
        'conditions' => array('(menu.menu_id = 0 or menu.menu_id IS NULL) and '
            . 'grupomenu.nome = ? and menu.data_exclusao is null', $left_menu_name),
        'order' => 'menu.ordem asc'
    ));
    
    //Monta submenus de primeiro nível
    $submenus_0 = array();
    
    foreach($menus as $key => $menu) {
        $submenus = \app\menu::all(array(
            'from' => 'app_menu as menu',
            'select' => 'menu.*',
            'joins' => array($joingrupo, $joinmodulo),
            'conditions' => array('menu.menu_id = ? and grupomenu.nome = ? and menu.data_exclusao is null', 
                $menu->id, $left_menu_name),
            'order' => 'menu.ordem asc'
        ));
        $submenus_0[$key] = $submenus;
    }
    
    //Monta submenus de segundo nivel
    $submenus_1 = array();
    
    foreach($submenus_0 as $sub) {
        foreach ($sub as $key => $submenu) {
            $submenus = \app\menu::all(array(
                'from' => 'app_menu as menu',
                'select' => 'menu.*',
                'joins' => array($joingrupo, $joinmodulo),
                'conditions' => array('menu.menu_id = ? and grupomenu.nome = ?', 
                    $submenu->id, $left_menu_name),
                'order' => 'menu.ordem asc'
            ));
            $submenus_1[$key] = $submenus;
        }
    }
    
} else {
    //Armazena as diferenciações de grupo
    $user_type = ($_SESSION['app']['grupo'] == "Especial") 
            ? 'usuario_id' : 'grupousuario_id';
    $user_type_session = ($_SESSION['app']['grupo'] == "Especial") 
            ? 'usuario_id' : 'grupo_id';
    
    $menus = \app\menu::all(array(
        'from' => 'app_menu as menu',
        'select' => 'menu.*',
        'joins' => array($joingrupo, $joinmodulo, $joinpermissao),
        'conditions' => array("(menu.menu_id = 0 or menu.menu_id IS NULL) and "
            . "grupomenu.nome = ? and menu.data_exclusao is null and "
            . "((menu.modulo_id is null) or "
            . "permissao.read = 1 and permissao.$user_type = ?)", 
            $left_menu_name, $_SESSION['app'][$user_type_session]),
        'order' => 'menu.ordem asc'
    ));

    //Monta submenus de primeiro nível
    $submenus_0 = array();
    foreach($menus as $key => $menu) {
        $submenus = \app\menu::all(array(
            'from' => 'app_menu as menu',
            'select' => 'menu.*',
            'joins' => array($joingrupo, $joinmodulo, $joinpermissao),
            'conditions' => array("menu.menu_id = ? and grupomenu.nome = ? and "
                . "menu.data_exclusao is null and "
                . "((menu.modulo_id is null) or "
                . "permissao.read = 1 and permissao.$user_type = ?)", 
                $menu->id, $left_menu_name, $_SESSION['app'][$user_type_session]),
            'order' => 'menu.ordem asc'
        ));
        
        $submenus_0[$key] = $submenus;
    }
    
    //Monta submenus de segundo nivel
    $submenus_1 = array();
    
    foreach($submenus_0 as $sub) {
        foreach ($sub as $key => $submenu) {
            $submenus = \app\menu::all(array(
                'from' => 'app_menu as menu',
                'select' => 'menu.*',
                'joins' => array($joingrupo, $joinmodulo, $joinpermissao),
                'conditions' => array("menu.menu_id = ? and grupomenu.nome = ? and "
                    . "menu.data_exclusao is null and "
                    . "((menu.modulo_id is null) or "
                    . "permissao.read = 1 and permissao.$user_type = ?)", 
                    $submenu->id, $left_menu_name, $_SESSION['app'][$user_type_session]),
                'order' => 'menu.ordem asc'
            ));

            $submenus_1[$key] = $submenus;
        }
    }
}

//Coloca os menus no template
foreach ($menus as $key_menu => $menu) {
    $tpl->menu = $menu;
    $has_submenu0 = 0;

    //Checa se existe e, se sim, insere submenus nível 1
    if (isset($submenus_0[$key_menu])) {
        
        foreach ($submenus_0[$key_menu] as $key_submenu_0 => $submenu_0) {
            $tpl->submenu0 = $submenu_0;
            $has_submenu1 = 0;
            
            //Checa se existe e, se sim, insere submenus nível 2
            if (isset($submenus_1[$key_submenu_0])) {
                
                foreach ($submenus_1[$key_submenu_0] as $key_submenu_1 => $submenu_1) {
                   $tpl->submenu1 = $submenu_1;
                   $tpl->block('block_submenu1');
                }
                 
                //Se houver 1 ou mais dentro do array, marca que o menu tem submenu
                if (count($submenus_1[$key_submenu_0]) > 0) {
                    $has_submenu1 = 1;
                    $tpl->block('block_has_submenu1');
                }
                 
            }
            $tpl->has_submenu1 = $has_submenu0;
            
            //Se for um menu apenas de listagem de submenus e não houver submenus, não
            //roda o menu
            if(!empty($has_submenu1) or (!empty($submenu_0->url) and $submenu_0->url != '#')) {
                $tpl->block('block_submenu0');
            }
        }

        //Se houver 1 ou mais dentro do array, marca que o menu tem submenu
        if (count($submenus_0[$key_menu]) > 0) {
            $has_submenu0 = 1;
            $tpl->block('block_has_submenu0');
        }
    }
    $tpl->has_submenu0 = $has_submenu0;
    
    //Se for um menu apenas de listagm de submenus e não houver submenus, não
    //roda o menu
    if(!empty($has_submenu0) or (!empty($menu->url) and $menu->url != '#'))
        $tpl->block('block_menu');
}

if (isset($_GET['error'])) {
    $tpl->tituloalerta = 'Erro';
    $tpl->mensagemalerta = $_GET['error'];
    $tpl->tipoalerta = 'danger';
    $tpl->icone = 'fa fa-ban';
    $tpl->block('block_alert');
}
if (isset($_GET['success'])) {
    $tpl->tituloalerta = 'Sucesso';
    $tpl->mensagemalerta = $_GET['success'];
    $tpl->tipoalerta = 'success';
    $tpl->icone = 'glyphicon glyphicon-ok';
    $tpl->block('block_alert');
}
$this->master_page = $tpl;

