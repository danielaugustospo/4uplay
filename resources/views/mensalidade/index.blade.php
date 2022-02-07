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

    td {
        font-size: 13;
    }

    .k-grid-header .k-header>.k-link {
        font-size: 13;
    }
</style>

@can('mensalidade-list')


<div class="container p-2" style="background-color:#b0b0b0; ">
    <h1 class="pt-2 pb-2 text-center" style="font-family: system-ui;"><b>Mensalidade</b></h1>
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
                            url: "{{ route('listamensalidade') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                            // headers: { 'Authorization': 'Bearer {{ $_COOKIE['gerenciamento_4uplay_session'] }}' },
                        },
                        update: {
                            url: "{{ route('atualizamensalidade') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                            // type: "post",
                        },
                        destroy: {
                            url: "{{ route('excluimensalidade') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                        },
                        create: {
                            url: "{{ route('criamensalidade') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                        },
                        parameterMap: function(options, operation) {

                                if (operation !== "read" && options.models) {

                                if (operation == "create" || operation == "update") {
                                    if (operation == "create") {
                                        var titulo = 'Mensalidade incluída!';
                                    }
                                    if (operation == "update") {
                                        var titulo = 'Mensalidade atualizada!';
                                    }
                                    Swal.fire(
                                        titulo,
                                        'Ano: ' + options.models[0].ano +
                                        ', Valor: R$' +  options.models[0].valor,
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
                                ano: {
                                    type: "string",
                                    validation: {
                                        required: true
                                    }
                                },

                                valor: {
                                    type: "number",
                                    validation: {
                                        required: true,
                                        min: 1
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

                @can('mensalidade-create')
                @include('layouts/customizacoestabela', ['permissaocriacao' => '1'])
                @else
                @include('layouts/customizacoestabela', ['permissaocriacao' => '0'])
                @endcan
                    columns: [{
                            field: "ano",
                            title: "Ano",
                            filterable: true,
                            width: "100px",
                            editor: inputTipoAno
                        },

                        {
                            field: "valor",
                            title: "Valor",
                            format: "{0:c}",
                            filterable: true,
                            width: "100px"
                        },

                        {
                            field: "created_at",
                            title: "Data Criação",
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
                                @can('mensalidade-delete') {
                                    name: "Excluir",
                                    click: function(e) {
                                        e.preventDefault();
                                        var tr = $(e.target).closest(
                                            "tr"); // get the current table row (tr)
                                        var data = this.dataItem(tr);
                                        // window.location.href = location.href + '/' + data.id;

                                        Swal.fire({
                                            title: 'Tem certeza que deseja remover esta mensalidade?',
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
                                                var url = "{{ route('excluimensalidade') }}";
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
                                                                    'Mensalidade removida!',
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

                
            function inputTipoAno(container, options) {


            // $('<input type="month" required name="' + options.field + '"/>').appendTo(container);
            $('<input type="number" min="2021" max="2099" step="1" value="2022" required name="' + options.field + '"/>').appendTo(container);
            }
            });




            @include('layouts/filtradata')


            // function customBoolEditor(container, options) {
            //     $('<input class="k-checkbox" type="checkbox" name="Discontinued" data-type="boolean" data-bind="checked:Discontinued">').appendTo(container);
            // }
        </script>
    </div>
</div>

@elsecan('mensalidade-list')
<h1>Acesso Não Autorizado</h1>
@endcan



@endsection