<?php

$tpl = new \mwwork\view("app/painelCMS/index");
        $tpl->addFile("head", "app/painelCMS/head");
        $tpl->addFile("header", "app/painelCMS/header");
        $tpl->addFile("sidebar", "app/painelCMS/sidebar");
        $tpl->title = 'Sistema Maquinaweb' . ' - ';
//        $tpl->titulopagina = $this->titulopagina;
//       $tpl->descricao = $this->descricao;
        $tpl->pasta = HOME_URI . '/var/';
        if(!CHECA_PERMISSAO) {
            $menus = \app\menu::all();
        }
        else{
        if (isset($_SESSION['userfoto'])) 
            $tpl->userfoto = $_SESSION['userfoto'];
        $tpl->url = HOME_URI;
        if (isset($_SESSION['nome']))
            $tpl->session_nome = $_SESSION['nome'];
//        if ($this->action != "edit")
//            $tpl->content = replace($conteudo, '{', '{{_}');
//        else
//            $tpl->content = $conteudo;
        $url = '/'.get('path');
        $joinstring1 = "LEFT JOIN modulo m ON(menu.id = m.menu_id)";
        $joinstring2 = "LEFT JOIN permissao p ON(m.id = p.modulo_id)";
        if ($_SESSION['grupo'] == "Especial") {
            $menus = \app\menu::all(array('joins' => array($joinstring1, $joinstring2),
                'conditions' => array('menu.menu_id=0 and (menu.url = "#" or ((p.read = 1 or p.create = 1 or p.update = 1 or p.delete = 1) and p.usuario_id = ?))', $_SESSION['usuario_id']), 'order' => 'ordem asc'));
        }
        else {
            $menus = \app\menu::all(array('joins' => array($joinstring1, $joinstring2),
                'conditions' => array('menu.menu_id=0 and (menu.url = "#" or ((p.read = 1 or p.create = 1 or p.update = 1 or p.delete = 1) and p.grupousuario_id = ?))', $_SESSION['grupo_id']), 'order' => 'ordem asc'));
        }
        if ($_SESSION['grupo'] == "Especial") {
            //Usuario é do grupo especial, pega permissões por usuario
            foreach ($menus as $menu) {
                $submenus = \app\menu::all(array('joins' => array($joinstring1, $joinstring2), 'conditions' => array("menu.menu_id = ? and (p.read = 1 or p.create = 1 or p.update = 1 or p.delete = 1) and p.usuario_id = ? ", $menu->id, $_SESSION['usuario_id']), "order" => "ordem asc", 'group' => 'id'));
                $tpl->menu = $menu;
                if (count($submenus) != 0) {
                    foreach ($submenus as $submenu) {
                        $tpl->submenu = $submenu;
                        //echo $url . "\n".$submenu->url. "\n" ;
                        if ($submenu->url == replace($url, '/form/', '/grid/') or $submenu->url == $url) {
                            $tpl->titulopagina = $submenu->nome;
                            $tpl->menuprincipal = $menu->nome;
                            $tpl->ac_pai = 'active';
                            $tpl->ac_sub = 'active';
                        } else {
                            $tpl->clear('ac_sub');
                        }
                        $tpl->block('rmenu');
                    }
                    $tpl->block('rtreeview');
                    $tpl->treeview = 'treeview';
                    $tpl->block('rsubmenuicon');
                    $tpl->block('rsubmenu');
                } else {
                    $tpl->clear('ac_pai');
                    if ($menu->url == $url)
                        $tpl->ac_pai = 'active';
                    else
                        $tpl->clear('ac_pai');
                    if ($menu->url != '#') {
                        if($menu->url == replace($url, '/form/', '/grid/') or $menu->url == $url) {
                            $tpl->titulopagina = $menu->nome;
                            $tpl->clear('menuprincipal');
                        }
                        $tpl->block('rsubmenu');
                    }
                    //$tpl->block('rmenusolo');
                }
                $tpl->clear('ac_pai');
            }
        }
        //Usuario não é do grupo especial, pega permissões por grupo
        else {
            foreach ($menus as $menu) {
                $grupo = \app\grupousuario::find_by_id($_SESSION['grupo_id']);
                $joinstring1 = "LEFT JOIN modulo m ON(menu.id = m.menu_id)";
                $joinstring2 = "LEFT JOIN permissao p ON(m.id = p.modulo_id)";
                $submenus = \app\menu::all(array('joins' => array($joinstring1, $joinstring2), 'conditions' => array("menu.menu_id = ? and (p.read = 1 or p.create = 1 or p.update = 1 or p.delete = 1) and p.grupousuario_id = ? ", $menu->id, $grupo->id), "order" => "ordem asc", 'group' => 'id'));
                $tpl->menu = $menu;
                if (count($submenus) != 0) {
                    foreach ($submenus as $submenu) {
                        $tpl->submenu = $submenu;
                        //echo $url . "\n".$submenu->url. "\n" ;
                        if ($submenu->url == replace($url, '/form/', '/grid/') or $submenu->url == $url) {
                            $tpl->titulopagina = $submenu->nome;
                            $tpl->menuprincipal = $menu->nome;
                            $tpl->ac_pai = 'active';
                            $tpl->ac_sub = 'active';
                        } else {
                            $tpl->clear('ac_sub');
                        }
                        $tpl->block('rmenu');
                    }
                    $tpl->block('rtreeview');
                    $tpl->treeview = 'treeview';
                    $tpl->block('rsubmenuicon');
                    $tpl->block('rsubmenu');
                } else {
                    $tpl->clear('ac_pai');
                    if ($menu->url == $url)
                        $tpl->ac_pai = 'active';
                    else
                        $tpl->clear('ac_pai');
                    if ($menu->url != '#') {
                        if($menu->url == replace($url, '/form/', '/grid/') or $menu->url == $url) {
                            $tpl->titulopagina = $menu->nome;
                            $tpl->clear('menuprincipal');
                        }
                        $tpl->block('rsubmenu');
                    }
                    //$tpl->block('rmenusolo');
                }
                $tpl->clear('ac_pai');
            }
        }
        $configuracao = \app\configuracao::find($_SESSION['configuracao_id']);
        $tpl->dominio = $configuracao->dominio;
        if ($tpl->exists('confnome'))
            $tpl->confnome = $configuracao->nome;
        $count = 0;
        $chamados = chamado::all(array('conditions' => array('configuracao_id = ? and resolvido = 0', $_SESSION['configuracao_id'])));
        foreach ($chamados as $chamado) {
            $interacao = \app\iteracaochamado::first(array('conditions' => array('chamado_id = ?', $chamado->id), 'order' => 'data desc'));
            if (is_object($interacao)) {
                if ($interacao->usuario_id != $_SESSION['usuario_id'] and ! $interacao->visualizado) {
                    $tpl->ch = $chamado;
                    $tpl->block('bmessage');
                    $count++;
                }
            }
        }
        $tpl->nc = $count;
        if (intval($tpl->nc) == 0) {
            $tpl->ncstyle = "display: none;";
        } else {
            $tpl->ncstyle = "";
        }
        }
        
        
        
//        if (!isset($_SESSION['cliente_id'])) {
//            
//            //Limite de sites para o select
//            $limite_sites = 8;
//            
//            $configuracoes = configuracaousuario::all(array(
//                'conditions' => array('usuario_id = ?', $_SESSION['usuario_id']),
//                'limit' => $limite_sites,
//                'order' => 'ultimo_acesso desc'
//            ));
//            foreach($configuracoes as $configuracao) {
//                $tpl->menuconfiguracaonome = $configuracao->configuracao->nome;
//                $tpl->confid = $configuracao->configuracao->id;
//                $tpl->urlatual = $_SERVER['REQUEST_URI'];
//                if ($configuracao->configuracao->id == $_SESSION['configuracao_id'])
//                    $tpl->block('bconfok');
//                $tpl->block('bconf');
//            }
//            if (checapermissao('cadastro/configuracao', 'read', false))
//                $tpl->block('bselectfooter');
//        }

        if (isset($_GET['erro'])) {
            $tpl->tituloalerta = 'Erro';
            $tpl->mensagemalerta = $_GET['erro'];
            $tpl->tipoalerta = 'danger';
            $tpl->icone = 'fa fa-ban';
            $tpl->block('balerta');
        }
        if (isset($_GET['mensagem'])) {
            $tpl->tituloalerta = 'Sucesso';
            $tpl->mensagemalerta = $_GET['mensagem'];
            $tpl->tipoalerta = 'success';
            $tpl->icone = 'glyphicon glyphicon-ok';
            $tpl->block('balerta');
        }
       $this->master_page = $tpl;

