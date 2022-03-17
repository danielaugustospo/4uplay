@extends('layouts.app')
@section('content')
<?php
$permissaocriacao = "totem-create";
?>
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

    td {
        font-size: 13;
    }

    .k-grid-header .k-header>.k-link {
        font-size: 13;
    }
</style>

@can('totem-list')


<div class="container p-2" style="background-color:#b0b0b0; ">
    <h1 class="pt-2 pb-2 text-center" style="font-family: system-ui;"><b>Totens</b></h1>
    <div id="example">
        <div id="grid"></div>

        <script>

            $(document).ready(function() {
                dataSource = new kendo.data.DataSource({
                    transport: {
                        read: {
                            url: "{{ route('listatotem') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                            // headers: { 'Authorization': 'Bearer {{ $_COOKIE['gerenciamento_4uplay_session'] }}' },
                        },
                        update: {
                            url: "{{ route('atualizatotem') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                            // type: "post",
                        },
                        destroy: {
                            url: "{{ route('excluitotem') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                        },
                        create: {
                            url: "{{ route('criatotem') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                        },
                        parameterMap: function(options, operation) {

                                if (operation !== "read" && options.models) {

                                if (operation == "create" || operation == "update") {
                                    if (operation == "create") {
                                        var titulo = 'Totem incluído!';
                                        var associado = 'Licenciado: ' + options.models[0].licenciado.name;
                                    }
                                    if (operation == "update") {
                                        var titulo = 'Totem atualizado!';
                                        var associado = ' ';
                                    }

                                    Swal.fire(
                                        titulo,
                                        'N° de série: ' + options.models[0].n_serie + '<br>'+
                                        associado,
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
                                n_serie: {
                                    type: "string",
                                    @can('totem-create')
                                        validation: {
                                            required: true
                                        },
                                    @else
                                        editable: false
                                    @endcan

                                },

                                idcliente: {
                                    type: "string",
                                    validation: {
                                        required: true
                                    }
                                },

                                dtassociado: {
                                    type: "date",
                                    editable: false
                                },
                            }
                        }
                    },

                });

                @can('totem-create')
                @include('layouts/customizacoestabela', ['permissaocriacao' => '1'])
                @else
                @include('layouts/customizacoestabela', ['permissaocriacao' => '0'])
                @endcan
                    columns: [{
                            field: "n_serie",
                            title: "N° SÉRIE",
                            filterable: true,
                            width: "100px",
                        },


                        @can('totem-create')

                        {
                            field: "licenciado",
                            title: "Licenciado",
                            filterable: true,
                            width: "100px",
                            editor: listaLicenciadoEClientes

                        },
                        @else
                        {
                            field: "cliente",
                            title: "Cliente",
                            filterable: true,
                            width: "100px",
                            editor: listaLicenciadoEClientes

                        },
                        @endcan

                        {
                            field: "dtassociado",
                            title: "Data Associação",
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
                                @can('totem-delete') {
                                    name: "Excluir",
                                    click: function(e) {
                                        e.preventDefault();
                                        var tr = $(e.target).closest(
                                            "tr"); // get the current table row (tr)
                                        var data = this.dataItem(tr);
                                        // window.location.href = location.href + '/' + data.id;

                                        Swal.fire({
                                            title: 'Tem certeza que deseja remover esta totem?',
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
                                                var url = "{{ route('excluitotem') }}";
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
                                                                    'Totem removido!',
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
                            width: 150,
                            exportable: false,
                            title: "Ações",
                        },

                    ],

                    editable: "inline"
                });



                function recarrega() {
                    $('#grid').data('kendoGrid').dataSource.read();
                    $('#grid').data('kendoGrid').refresh();
                }

                
            });





            @include('layouts/combos')
            @include('layouts/filtradata')

        </script>
    </div>
</div>

@elsecan('totem-list')
<h1>Acesso Não Autorizado</h1>
@endcan



@endsection