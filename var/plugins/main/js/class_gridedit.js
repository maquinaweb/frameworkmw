class gridedit{
    constructor(table) {
        if(gridedit.confirm_initializegridedit != true)
            this.initializegridedit();
        this.geratabelajson(table);
    }
    
    static confirm_initializegridedit(){
        return true;
    };
    
    //Funções para a geração de GRID dinâmico, não mexer!
    removeparametrosvazio(jsonparametros){
        var parametros = $.parseJSON(jsonparametros);
        $.each(parametros, function( key, value ) {
            var apaga = 0;
            $.each(parametros[key], function( keychild, valuechild ) {
                if(valuechild != '')
                    apaga = 1;
            });
            if(apaga == 0)
                parametros.splice(key,1); 
        });
        return JSON.stringify(parametros);
    }

    botaoremover(enabled){
        var disabled = "";
        if(enabled == 'false' || enabled == false)
            disabled = 'disabled';
        return '<button type="button" class="btn btn-danger removerrow" '+disabled+'><i class="fas fa-trash "></i></button>';
    }

    botaoeditar(enabled){
        var disabled = "";
        if(enabled == 'false' || enabled == false)
            disabled = 'disabled';
        return '<button type="button" class="btn btn-info editarrow" '+disabled+'><i class="fas fa-edit"></i></button><button type="button" class="btn btn-success salvarrow" '+disabled+' style="display:none;"><i class="fa fa-check-square-o"></i></button><button type="button" class="btn btn-danger cancelarrow" '+disabled+' style="display:none;"><i class=" fas fa-times"></i></button>';
    }

    botaoadicionar(){
        return '<button type="button" class="btn btn-success addrow"><i class="fa fa-plus"></i></button>';
    }

    gerainput(nome, placeholder, valor, tipo){
        var disabled = "";
        if(nome == 'id')
            disabled = "readonly";
        if(tipo == 'text'){
            return '<input name="'+nome+'" type="'+tipo+'" class="form-control" value="'+valor+'" '+disabled+' placeholder="'+placeholder+'">'
        }
    }
    
    geraselect(nome, valor, arrayoptions){
        var disabled = "";
        if(nome == 'id')
            disabled = "readonly";
        var selected = "";
        var count = 0;
        var selectinput = '<select name="'+nome+'" class="form-control" '+disabled+'>';
        $.each(arrayoptions, function( keychild, valuechild ) {
            if(valor == keychild || (valor == '' && count == 0))
                selected = 'selected';
            else
                selected = '';
            selectinput += '<option value="'+keychild+'" '+selected+'>'+valuechild+'</option>';
            count = 1;
        });
        selectinput += '</select>';
        return selectinput;
    }
    
    geraradios(nome, valor, arrayoptions){
        var disabled = "";
        if(nome == 'id')
            disabled = "readonly";
        var checked = "";
        var count = 0;
        var radioinput = '';
        $.each(arrayoptions, function( keychild, valuechild ) {
            if(valor == keychild || (valor == '' && count == 0))
                checked = 'checked';
            else
                checked = '';
            radioinput += '<input type="radio" name="'+nome+'" '+disabled+' '+checked+' value="'+keychild+'" style="margin-left: 7px;"/> '+valuechild;
            count = 1;
        });
        return radioinput;
    }

    geratabelajson(tabela){
        var varthis = this;
        var arraycolumns = tabela.data("columnsjson");
        var arrayselects = tabela.data("selectsjson");
        var arrayradios = tabela.data("radiosjson");
        if(arrayselects == "" || arrayselects == null || arrayselects == undefined){
            arrayselects = [];
        }
        if(arrayradios == "" || arrayradios == null || arrayradios == undefined){
            arrayradios = [];
        }
        tabela.html('');
        tabela.append('<thead></thead>');
        tabela.append('<tbody></tbody>');
        tabela.append('<tfoot></tfoot>');
        var stringjson = $(tabela.data('inputjson')).val();
        if((stringjson == '' || stringjson == '[]') && (arraycolumns == null || arraycolumns == ""))
            return;
        //remover vazios
        stringjson = this.removeparametrosvazio(stringjson);
        //atualiza inputjson
        $(tabela.data('inputjson')).val(stringjson);
        //converter meu json em array
        var arraytable = $.parseJSON(stringjson);
        //se estiver vazio, sai
        if(arraytable.length == 0){
            if(arraycolumns != null && arraycolumns != ""){
                tabela.find('thead').append('<tr></tr>');
                tabela.find('tfoot').append('<tr></tr>');
                $.each(arraycolumns, function( keychild, valuechild ) {
                    tabela.find('thead').find('tr').append('<td><strong>'+valuechild+'</strong></td>');                    
                    if(arrayselects[keychild] != "" && arrayselects[keychild] != null && arrayselects[keychild] != undefined){
                        tabela.find('tfoot').find('tr').append('<td>'+varthis.geraselect(keychild, '', arrayselects[keychild])+'</td>');
                    }else if(arrayradios[keychild] != "" && arrayradios[keychild] != null && arrayradios[keychild] != undefined){
                        tabela.find('tfoot').find('tr').append('<td>'+varthis.geraradios(keychild, '', arrayradios[keychild])+'</td>');
                    }else{
                        tabela.find('tfoot').find('tr').append('<td>'+varthis.gerainput(keychild, valuechild, '', 'text')+'</td>');
                    }
                    
                });
                tabela.find('thead').find('tr').append('<td><strong>Ações</strong></td>');
                tabela.find('tfoot').find('tr').append('<td style="width: 120px;">'+varthis.botaoadicionar()+'</td>');
            }
        }
        $.each(arraytable, function( key, value ) {
            //string de configuração individual de linha
            var varconftabr = {remove:'true', edit:'true'};
            if(key == 0){
                tabela.find('thead').append('<tr></tr>');
                tabela.find('tfoot').append('<tr></tr>');
            }
            tabela.find('tbody').append('<tr></tr>');
            if(arraycolumns != null && arraycolumns != "")
                var arrayorder = arraycolumns;
            else
                var arrayorder = arraytable[key];
            $.each(arrayorder, function( keychild, valuechild ) {
                var titlechild = keychild;
                if(arraycolumns != null && arraycolumns != ""){
                    titlechild = valuechild;
                }
                //pegar a primeira linha e usar como base
                if(key == 0){
                    tabela.find('thead').find('tr').append('<td><strong>'+titlechild+'</strong></td>');
                    if(arrayselects[keychild] != "" && arrayselects[keychild] != null && arrayselects[keychild] != undefined){
                        tabela.find('tfoot').find('tr').append('<td>'+varthis.geraselect(keychild, '', arrayselects[keychild])+'</td>');
                    }else if(arrayradios[keychild] != "" && arrayradios[keychild] != null && arrayradios[keychild] != undefined){
                        tabela.find('tfoot').find('tr').append('<td>'+varthis.geraradios(keychild, '', arrayradios[keychild])+'</td>');
                    }else{
                        tabela.find('tfoot').find('tr').append('<td>'+varthis.gerainput(keychild, titlechild, '', 'text')+'</td>');
                    }
                }
                //escrever nas demais colunas
                if(arrayselects[keychild] != "" && arrayselects[keychild] != null && arrayselects[keychild] != undefined){
                    tabela.find('tbody').find('tr').last().append('<td>'+arrayselects[keychild][arraytable[key][keychild]]+'</td>');
                }else if(arrayradios[keychild] != "" && arrayradios[keychild] != null && arrayradios[keychild] != undefined){
                    tabela.find('tbody').find('tr').last().append('<td>'+arrayradios[keychild][arraytable[key][keychild]]+'</td>');
                }else{
                    tabela.find('tbody').find('tr').last().append('<td>'+arraytable[key][keychild]+'</td>');
                }
            });
            if(key == 0){
                tabela.find('thead').find('tr').append('<td><strong>Ações</strong></td>');
                tabela.find('tfoot').find('tr').append('<td style="width: 120px;">'+varthis.botaoadicionar()+'</td>');
            }
            if(arraytable[key]['edit'] != '' || arraytable[key]['edit'] != null)
                varconftabr['edit'] = arraytable[key]['edit'];
            if(arraytable[key]['remove'] != '' || arraytable[key]['remove'] != null)
                varconftabr['remove'] = arraytable[key]['remove'];
            tabela.find('tbody').find('tr').last().append('<td style="width: 120px;"></td>');
            tabela.find('tbody').find('tr').last().find('td').last().append(varthis.botaoeditar(varconftabr['edit']));
            tabela.find('tbody').find('tr').last().find('td').last().append(varthis.botaoremover(varconftabr['remove']));
        });
    }

    initializegridedit(){
        
        var varthis = this;

        $(document).on('click', '.addrow', function(){
            var tabela = $(this).parent().parent().parent().parent();
            var parametros = $.parseJSON($(tabela.data('inputjson')).val());
            var arrayselects = tabela.data("selectsjson");
            if(arrayselects == "" || arrayselects == null || arrayselects == undefined)
                arrayselects = [];
            var arrayradios = tabela.data("radiosjson");
            if(arrayradios == "" || arrayradios == null || arrayradios == undefined)
                arrayradios = [];
            var newpar = {};
            var newrow = "";
            var apaga = 0;
            $.each(tabela.find('tfoot').find(':input').serializeArray(), function(i, field) {
                if(field.value != "")
                    apaga = 1;
                newpar[field.name] = field.value;
                if(arrayselects[field.name] != "" && arrayselects[field.name] != null && arrayselects[field.name] != undefined){
                    newrow = newrow + "<td>" + arrayselects[field.name][field.value] + "</td>";
                }else if(arrayradios[field.name] != "" && arrayradios[field.name] != null && arrayradios[field.name] != undefined){
                    newrow = newrow + "<td>" + arrayradios[field.name][field.value] + "</td>";
                }else{
                    newrow = newrow + "<td>" + field.value + "</td>";
                    tabela.find('tfoot').find("*[name='"+field.name+"']").val('');
                }
            });
            if(apaga == 1){
                parametros.push(newpar);
                $(tabela.data('inputjson')).val(JSON.stringify(parametros));
                tabela.find('tbody').append("<tr>" + newrow + '<td>'+varthis.botaoeditar()+varthis.botaoremover()+'</td></tr>');
            }
            return false;
        });

        $(document).on('click', '.removerrow', function(){
            if(!confirm("Tem certeza que deseja executar essa ação?"))
                return false;
            var row = $(this).parent().parent();
            var tabela = row.parent().parent();
            var parametros = $.parseJSON($(tabela.data('inputjson')).val());
            parametros.splice(row.index(),1);
            $(tabela.data('inputjson')).val(JSON.stringify(parametros));
            row.remove();
        }); 

        $(document).on('click', '.editarrow', function(){
            var row = $(this).parent().parent();
            var tabela = row.parent().parent();
            var arrayselects = tabela.data("selectsjson");
            if(arrayselects == "" || arrayselects == null || arrayselects == undefined)
                arrayselects = [];
            var arrayradios = tabela.data("radiosjson");
            if(arrayradios == "" || arrayradios == null || arrayradios == undefined)
                arrayradios = [];
            //mostra botao de salvar e cancelar, esses botoes devem ser inseridos na funcao botaoeditar()
            //faz o botoes de editar e remover desaparecer, dessa linha
            row.find('.btn').css('display', 'none');
            row.find('.salvarrow, .cancelarrow').css('display', 'inline-block');
            //salva qual a posicao dessa linha
            var posicao = row.index();
            //copia o codigo na linha tfoot e cola nela
            var tfoottr = tabela.find('tfoot tr').last().html();
            var tfoottrtdlast = tabela.find('tfoot tr td').last().html();
            var rowtdlast = row.find('td').last().html();
            var rowresult = tfoottr.replace(tfoottrtdlast, rowtdlast);
            var temp = $('<div/>').html(rowresult);
            temp.find(':input').each(function (index, value) { 
                $(this).attr('name', $(this).attr('name') + '_g' + posicao);
            });
            row.html(temp.html());
            //pega os dados do json dessa linha, conforme a posicao e preenche os campos
            var parametros = $.parseJSON($(tabela.data('inputjson')).val());
            $.each(parametros[posicao], function( keychild, valuechild ) {
                if(arrayradios[keychild] != "" && arrayradios[keychild] != null && arrayradios[keychild] != undefined){
                    $.each(row.find("*[name='"+keychild+"_g"+posicao+"']"), function(){
                        if($(this).val() == valuechild)
                            $(this).attr('checked', 'checked');  
                    });
                }else{
                    row.find("*[name='"+keychild+"_g"+posicao+"']").val(valuechild);
                }
            });
        });

        $(document).on('click', '.salvarrow', function(){
            var row = $(this).parent().parent();
            var tabela = row.parent().parent();
            var arrayselects = tabela.data("selectsjson");
            if(arrayselects == "" || arrayselects == null || arrayselects == undefined)
                arrayselects = [];
            var arrayradios = tabela.data("radiosjson");
            if(arrayradios == "" || arrayradios == null || arrayradios == undefined)
                arrayradios = [];
            row.find('.btn').css('display', 'inline-block');
            row.find('.salvarrow, .cancelarrow').css('display', 'none');
            //salva qual a posicao dessa linha
            var posicao = row.index();
            var parametros = $.parseJSON($(tabela.data('inputjson')).val());
            //pega os valores dos inputs
            var apaga = 0;
            var newrow = "";
            $.each(row.find(':input').serializeArray(), function(i, field) {
                if(field.value != "")
                    apaga = 1;
                field.name = field.name.replace('_g' + posicao, '');
                parametros[posicao][field.name] = field.value;
                if(arrayselects[field.name] != "" && arrayselects[field.name] != null && arrayselects[field.name] != undefined){
                    newrow += "<td>" + arrayselects[field.name][field.value] + "</td>";
                }else if(arrayradios[field.name] != "" && arrayradios[field.name] != null && arrayradios[field.name] != undefined){
                    newrow += "<td>" + arrayradios[field.name][field.value] + "</td>";
                }else{
                    newrow += "<td>" + field.value + "</td>";
                }
            });
            if(apaga == 1){
                $(tabela.data('inputjson')).val(JSON.stringify(parametros));
                newrow += "<td>" + row.find('td').last().html() + "</td>";
                row.html(newrow);
            }
        });

        $(document).on('click', '.cancelarrow', function(){
            var row = $(this).parent().parent();
            var tabela = row.parent().parent();
            var arrayselects = tabela.data("selectsjson");
            if(arrayselects == "" || arrayselects == null || arrayselects == undefined)
                arrayselects = [];
            var arrayradios = tabela.data("radiosjson");
            if(arrayradios == "" || arrayradios == null || arrayradios == undefined)
                arrayradios = [];
            row.find('.btn').css('display', 'inline-block');
            row.find('.salvarrow, .cancelarrow').css('display', 'none');
            //voltar a tr como era antes, usando a posicao do json
            var posicao = row.index();
            var parametros = $.parseJSON($(tabela.data('inputjson')).val());
            var newrow = "";
            $.each(row.find(':input').serializeArray(), function(i, field) {
                field.name = field.name.replace('_g' + posicao, '');
                if(arrayselects[field.name] != "" && arrayselects[field.name] != null && arrayselects[field.name] != undefined){
                    newrow += "<td>" + arrayselects[field.name][parametros[posicao][field.name]] + "</td>";
                }else if(arrayradios[field.name] != "" && arrayradios[field.name] != null && arrayradios[field.name] != undefined){
                    newrow += "<td>" + arrayradios[field.name][parametros[posicao][field.name]] + "</td>";
                }else{
                    newrow += "<td>" + parametros[posicao][field.name]  + "</td>";
                }
                //newrow += "<td>" + parametros[posicao][field.name] + "</td>"
            });
            newrow += "<td>" + row.find('td').last().html() + "</td>";
            row.html(newrow);
        });
        
        gridedit.confirm_initializegridedit = true;
    } 
}