<head>
    {{-- <meta charset="utf-8"> --}}
    <title>Pipeline</title>
</head>

@extends('layouts.app')
@section('content')

@php $permissaocriacao = "pipeline-create"; @endphp

<style>
    .critical { color: rgb(236, 51, 51) !important; }

    .warning { color: rgb(240, 174, 75) !important; }

    .ok { color: rgb(115, 171, 197) !important; }
</style>


@can('pipeline-list')


<div class="p-1" style="background-color:#b0b0b0; ">
    <h2 class="pt-2 pb-2 text-center" style="font-family: system-ui;"><b> Pipeline (anúncios)</b></h2>

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
                            url: "{{ route('listapipeline') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?>,
                            dataType: "json",
                            // headers: { 'Authorization': 'Bearer {{ $_COOKIE['gerenciamento_4uplay_session'] }}' },
                        },
                        update: {
                            url: "{{ route('atualizapipeline') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?>,
                            dataType: "json",
                            // type: "post",
                        },
                        destroy: {
                            url: "{{ route('excluipipeline') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?>,
                            dataType: "json",
                        },
                        create: {
                            url: "{{ route('criapipeline') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?>,
                            dataType: "json",
                        },
                        parameterMap: function(options, operation) {
                            if (operation !== "read" && options.models) {

                                if (operation == "create" || operation == "update") {
                                    if (operation == "create") {
                                        var titulo = 'Pipeline incluída!';
                                    }
                                    if (operation == "update") {
                                        var titulo = 'Pipeline atualizada!';
                                    }
                                    Swal.fire(
                                        titulo,
                                        'Cliente: ' + options.models[0].cliente +
                                        ', Qualificação: ' + options.models[0].qualificacao +
                                        ', Proposta: R$' + options.models[0].proposta +
                                        ', Negociação: ' + options.models[0].negociacao +
                                        ', Fechamento: ' + options.models[0].fechamento,
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
                                // id: {
                                //     editable: false,
                                //     nullable: true,
                                //     type: "string"

                                // },
                                cliente: {
                                    validation: {
                                        required: true
                                    }
                                },
                                qualificacao: {
                                    type: "string"
                                },
                                proposta: {
                                    type: "number",
                                    validation: {
                                        required: true,
                                        min: 0.00
                                    }
                                },
                                @can('pipeline-delete')
                                licenciado: {
                                    type: "string",
                                    editable: false
                                },
                                @endcan
                                fechamento: {
                                    type: "number",
                                    validation: {
                                        required: true,
                                        min: 0.00
                                    }
                                },
                                negociacao: {
                                    type: "string",
                                    validation: {
                                        required: true,
                                        maxlength:"25",
                                    }
                                },
                                created_at: {
                                    type: "date",
                                    required: true
                                },
                                datainicial: {
                                    type: "date",
                                    required: true
                                },
                                datafinal: {
                                    type: "date",
                                    required: true
                                },
                            }
                        }
                    },

                });

                @can('pipeline-create')
                @include('layouts/customizacoestabela', ['permissaocriacao' => '1'])
                @else
                @include('layouts/customizacoestabela', ['permissaocriacao' => '0'])
                @endcan

                    columns: [{
                            title: "Cliente",
                            field: "cliente",
                            filterable: {
                                cell: {
                                    template: filtraClientePipeline
                                },
                            },
                            width: "120px",
                            editor: listaClientes

                        },
                        {
                            field: "n_serie",
                            title: "Totem",
                            filterable: {
                                cell: {
                                    template: filtraTotemPipeline
                                }
                            },  
                            width: "100px",
                            editor: listaTotem
                        },
                        {
                            field: "qualificacao",
                            title: "Qualificação",
                            filterable: {
                                cell: {
                                    template: qualificacaoFilter
                                }
                            },                       
                     
                            width: "100px",
                            editor: categoryDropDownEditor
                        },
                        {
                            field: "proposta",
                            title: "Proposta",
                            format: "{0:n}",
                            filterable: true,
                            width: "100px"
                        },
                        @can('pipeline-delete') {
                            field: "licenciado",
                            title: "Licenciado",
                            filterable: true,
                            width: "120px",
                        },
                        @endcan {
                            field: "negociacao",
                            title: "Status<br>Negociação",
                            filterable: true,
                            width: "100px"
                        },
                        {
                            field: "fechamento",
                            title: "Valor<br>Fechamento",
                            format: "{0:n}",
                            filterable: true,
                            width: "100px"
                        },
                        {
                            field: "datainicial",
                            title: "Data da<br>Proposta",
                            width: "160px",
                            format: "{0:dd/MM/yyyy}",
                            filterable: {
                                cell: {
                                    template: betweenFilter
                                }
                            }
                        },
                        {
                            field: "datafinal",
                            title: "Data do<br>Encerramento",
                            width: "160px",
                            format: "{0:dd/MM/yyyy}",
                            filterable: {
                                cell: {
                                    template: betweenFilterLocal
                                }
                            }
                        },
                        {
                            command: [{
                                    name: "edit",
                                    text: "Editar"
                                },
                                @can('pipeline-delete') {
                                    name: "Excluir",
                                    click: function(e) {
                                        e.preventDefault();
                                        var tr = $(e.target).closest(
                                            "tr"); // get the current table row (tr)
                                        var data = this.dataItem(tr);
                                        // window.location.href = location.href + '/' + data.id;

                                        Swal.fire({
                                            title: 'Tem certeza que deseja remover esta pipeline?',
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
                                                var url = "{{ route('excluipipeline') }}";
                                                var params = 'id=' + data.id + '&cliente=' + data.cliente + "&acesso=" + <?php if (Auth::user()->id) : echo Auth::user()->id;
                                                                                                                            endif; ?>;

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
                                                                    'Pipeline removida!',
                                                                    'É possível ver esta ação no menu Pipeline -> Histórico.',
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
                            @can('pipeline-delete')
                                width: 150,
                            @else
                                width: 80,
                            @endcan
                            exportable: false,
                            title: "Ações",
                        },

                    ],
                    

                    dataBound: function(e) {
                        // get the index of the UnitsInStock cell
                        var columns = e.sender.columns;
                        var columnIndex = this.wrapper.find(".k-grid-header [data-field=" + "qualificacao" +
                            "]").index();

                        // iterate the table rows and apply custom cell styling
                        var rows = e.sender.tbody.children();
                        for (var j = 0; j < rows.length; j++) {
                            var row = $(rows[j]);
                            var dataItem = e.sender.dataItem(row);
                            console.log(dataItem);
                            var units = dataItem.get("qualificacao");

                            var cell = row.children().eq(columnIndex);
                            // $(rows[j]).addClass(getUnitsInStockClass(units));
                            $(cell).addClass(getUnitsInStockClass(units));
                        }
                        $(".critical").append($('<i class="pl-2 fas fa-fire"></i>'));
                        $(".warning").append($('<i class="pl-2 fas fa-mug-hot"></i>'));
                        $(".ok").append($('<i class="pl-2 fas fa-snowflake"></i>'));
                    },
                    editable: "inline"
                });



                function recarrega() {
                    $('#grid').data('kendoGrid').dataSource.read();
                    $('#grid').data('kendoGrid').refresh();
                }
            });


            function categoryDropDownEditor(container, options) {

                var data = [{
                        text: "Quente",
                        value: 'Quente'
                    },
                    {
                        text: "Morno",
                        value: 'Morno'
                    },
                    {
                        text: "Frio",
                        value: 'Frio'
                    },

                ];

                $('<input required name="' + options.field + '"/>')
                    .appendTo(container)
                    .kendoDropDownList({
                        dataSource: data,
                        dataTextField: "text",
                        dataValueField: "value"
                    });
            }

        @include('layouts/combos') 
        @include('layouts/filtradatapipeline')
        @include('layouts/filtraitenstabela')


        </script>

    </div>
 </div>

@elsecan('pipeline-list')
<h1>Acesso Não Autorizado</h1>
@endcan

@endsection