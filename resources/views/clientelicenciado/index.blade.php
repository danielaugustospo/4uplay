@extends('layouts.app')
@section('content')
<style>
    .critical {
        color: rgb(236, 51, 51) !important;
    }

    .warning {
        color: rgb(240, 174, 75) !important;
    }

    .ok {
        color: rgb(115, 171, 197) !important;
    }

</style>

@can('clienteslicenciado-list')


        <div class="p-1" style="background-color:#b0b0b0; ">
            <h2 class="pt-2 pb-2 text-center" style="font-family: system-ui;"><b> @if ($permiteListagemCompleta == 0)Meus @endif Clientes</b></h2>
        
            <div id="example">
                <div id="grid"></div>           

        <script>
            function getUnitsInStockClass(units) {
                if (units == 'Quente') {
                    return "critical";
                } else if (units == 'Morno') {
                    return "warning";
                } else if (units == 'Frio') {
                    return "ok";
                }
            }

            $(document).ready(function() {
                dataSource = new kendo.data.DataSource({
                    transport: {
                        read: {
                            url: "{{ route('listaclientelicenciado') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                            // headers: { 'Authorization': 'Bearer {{ $_COOKIE['gerenciamento_4uplay_session'] }}' },
                        },
                        update: {
                            url: "{{ route('atualizaclientelicenciado') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                            // type: "post",
                        },
                        destroy: {
                            url: "{{ route('excluiclientelicenciado') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                        },
                        create: {
                            url: "{{ route('criaclientelicenciado') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                        },
                        parameterMap: function(options, operation) {

                                if (operation !== "read" && options.models) {

                                if (operation == "create" || operation == "update") {
                                    if (operation == "create") {
                                        var titulo = 'Cliente incluído!';
                                    }
                                    if (operation == "update") {
                                        var titulo = 'Cliente atualizado!';
                                    }
                                    Swal.fire(
                                        titulo,
                                        'Nome: ' + options.models[0].c_nome,
                                        'success'
                                    );
                                }
                                return {
                                    models: kendo.stringify(options.models)
                                    
                                };
                                
                            }
                        }
                    },
                    batch: true,
                    pageSize: 20,
                    schema: {
                        model: {
                            id: "id",
                            fields: {
                                id: {
                                    type: "string",
                                    validation: {
                                        required: true
                                    }
                                },
                                name: {
                                    type: "string",
                                    editable: false

                                },
                                c_nome: {
                                    type: "string",
                                    validation: {
                                        required: true
                                    }
                                },
                                c_cnpj: {
                                    type: "string",
                                    validation: {
                                        required: true, maxlength:"14"
                                    }
                                },
                                c_email: {
                                    type: "string",
                                    validation: {
                                        email: true,
                                        required: true
                                    }
                                },
                                c_endereco: {
                                    type: "string",
                                    validation: {
                                        required: true, maxlength:"85"
                                    }
                                },
                                c_telefone: {
                                    type: "string",
                                    validation: {
                                        required: true, minlength:"8", maxlength:"11"
                                    }
                                },
                                c_estado: {
                                    type: "string",
                                    validation: {
                                        required: true, maxlength:"2", placeholder:"Sigla"
                                    }
                                },
                                c_municipio: {
                                    type: "string",
                                    validation: {
                                        required: true
                                    }
                                },

                                created_at: {
                                    type: "date",
                                    editable: false
                                },
                            }
                        }
                    },

                });

                @can('clienteslicenciado-create')
                @include('layouts/customizacoestabela', ['permissaocriacao' => '1'])
                @else
                @include('layouts/customizacoestabela', ['permissaocriacao' => '0'])
                @endcan
                    columns: [
                        { field: "c_nome", title: "Nome/Rz. Social", filterable: true, width: "100px" },
                        @if ($permiteListagemCompleta == 1)
                            { field: "name", title: "Licenciado", filterable: true, width: "100px"},
                        @endif
                        { field: "c_cnpj", title: "CNPJ", filterable: true, editor: ajustaCNPJ, width: "100px" },
                        { field: "c_email", title: "Email", filterable: true, width: "100px" },
                        { field: "c_endereco", title: "Endereço", filterable: true, width: "100px" },
                        { field: "c_telefone", title: "Telefone", filterable: true, editor: ajustaTelefone, width: "80px"},
                        { field: "c_estado", title: "Estado", filterable: true, width: "70px" },
                        { field: "c_municipio", title: "Município", filterable: true, width: "70px" },
                        // { field: "created_at", title: "Data Cadastro", filterable: true, width: "150px" },

                        {
                            field: "created_at",
                            title: "Data Cadastro",
                            width: "160px",
                            format: "{0:dd/MM/yyyy}",
                            filterable: {
                                cell: {
                                    template: betweenFilter
                                }
                            }
                        },

                        {
                            command: [{
                                    name: "edit",
                                    text: "Editar"
                                },
                                @can('clienteslicenciado-delete') {
                                    name: "Excluir",
                                    click: function(e) {
                                        e.preventDefault();
                                        var tr = $(e.target).closest(
                                            "tr"); // get the current table row (tr)
                                        var data = this.dataItem(tr);
                                        // window.location.href = location.href + '/' + data.id;

                                        Swal.fire({
                                            title: 'Tem certeza que deseja remover este cliente?',
                                            text: "Esta ação não poderá ser revertida!",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            cancelButtonText: 'Cancelar',
                                            confirmButtonText: 'Sim, desejo excluir!'
                                        }).then((result) => {
                                            if (result.isConfirmed) {

                                                var http = new XMLHttpRequest();
                                                var url = "{{ route('excluiclientelicenciado') }}";
                                                var params = 'id=' + data.id + '&ano=' + data.ano + "&acesso=" + <?php if (Auth::user()->id) : echo Auth::user()->id; endif; ?> ;

                                                http.open('POST', url, true);

                                                //Send the proper header information along with the request
                                                http.setRequestHeader('Content-type',
                                                    'application/x-www-form-urlencoded');
                                                var contador = 0;
                                                http.onreadystatechange =
                                                    function() { //Call a function when the state changes.
                                                        if (http.readyState != 4 && http.status != 200) {
                                                            while (contador < 1) {
                                                                Swal.fire(
                                                                    'Não foi possível processar a sua solicitação',
                                                                    'Ocorreu um erro ao executar o procedimento',
                                                                    'error');
                                                                contador++;
                                                            }
                                                        } else {
                                                            recarrega();
                                                            while (contador < 1) {
                                                                Swal.fire(
                                                                    'Cliente removido!',
                                                                    '',
                                                                    'success');
                                                                contador++;
                                                            }
                                                        }
                                                    }
                                                http.send(params);
                                            } else {
                                                recarrega();
                                            }
                                        })
                                    }
                                }
                                @endcan
                            ],
                            width: 90,
                            exportable: false,
                            title: "Ações",
                        },

                    ],

                    editable: "popup"
                });
                

                function recarrega() {
                    $('#grid').data('kendoGrid').dataSource.read();
                    $('#grid').data('kendoGrid').refresh();
                }

                
            });


            @include('layouts/filtradata')


            function ajustaCNPJ(container, options) {
                $('<input id="c_cnpj" data-text-field="c_cnpj" data-value-field="c_cnpj" data-bind="value:' + options.field + '"/>')
                    .appendTo(container)
                    .kendoMaskedTextBox({
                        mask: "00,000,000/0000-00",
                        value: options.model.c_cnpj
                    });
                }

            function ajustaTelefone(container, options) {
                $('<input id="c_cnpj" data-text-field="c_cnpj" data-value-field="c_cnpj" data-bind="value:' + options.field + '"/>')
                    .appendTo(container)
                    .kendoMaskedTextBox({
                        mask: "(00)90000-0000",
                        value: options.model.c_cnpj
                    });
                }


        </script>
    </div>
</div>

@else
<h1>Acesso Não Autorizado</h1>
@endcan



@endsection