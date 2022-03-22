@extends('layouts.app')
@section('content')
<?php
$permissaocriacao = "criativo-create";
$dataUm = "datacriacao";
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

</style>

@can('criativo-list')


        <div class="p-1" style="background-color:#b0b0b0; ">
            <h2 class="pt-2 pb-2 text-center" style="font-family: system-ui;"><b> Criativo</b></h2>
        
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
                            url: "{{ route('listacriativo') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                            // headers: { 'Authorization': 'Bearer {{ $_COOKIE['gerenciamento_4uplay_session'] }}' },
                        },
                        update: {
                            url: "{{ route('atualizacriativo') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                            // type: "post",
                        },
                        destroy: {
                            url: "{{ route('excluicriativo') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                        },
                        create: {
                            url: "{{ route('criacriativo') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?> ,
                            dataType: "json",
                        },
                        parameterMap: function(options, operation) {
                            if (operation !== "read" && options.models) {

                                if (operation == "create" || operation == "update") {
                                    if (operation == "create") {
                                        var titulo = 'Criativo incluído!';
                                    }
                                    if (operation == "update") {
                                        var titulo = 'Criativo atualizado!';
                                    }
                                    Swal.fire(
                                        titulo,
                                        'Cliente: ' + options.models[0].cliente +
                                        ', Criativo: ' + options.models[0].tipocriativo +
                                        ', Quantidade: ' + options.models[0].quantidade +
                                        ', Val Unit: R$' + options.models[0].valunit +
                                        ', Val Total: R$' + options.models[0].valunit * options.models[0].quantidade,
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
                                tipocriativo: {
                                    type: "string"
                                },
                                quantidade: {
                                    type: "number",
                                    validation: {
                                        required: true,
                                        min: 1
                                    }
                                },
                                valunit: {
                                    type: "number",
                                    validation: {
                                        required: true,
                                        min: 1
                                    }
                                },
                                valtotal: {
                                    type: "number",
                                    editable: false,
                                    // validation: {
                                    //     required: true,
                                    //     min: 1
                                    // }
                                },
                                @can('criativo-delete')
                                licenciado: {
                                    type: "string",
                                    editable: false
                                },
                                @endcan
                                // fechamento: {
                                //     type: "string",
                                //         validation: {
                                //         required: true
                                //     }
                                // },
                                datacriacao: {
                                    type: "date",
                                    editable: true
                                },
                                // created_at: {
                                //     type: "date",
                                //     editable: false
                                // },
                            }
                        }
                    },

                });

                @can('criativo-create')
                @include('layouts/customizacoestabela', ['permissaocriacao' => '1'])
                @else
                @include('layouts/customizacoestabela', ['permissaocriacao' => '0'])
                @endcan
                    columns: [
                        @can('criativo-delete') {
                            field: "licenciado",
                            title: "Licenciado",
                            filterable: true,
                            width: "100px",
                        },
                        @endcan
                        {
                            title: "Cliente",
                            field: "cliente",
                            filterable: {
                                cell: {
                                    template: filtraClientePipeline
                                },
                            },                            
                            width: "100px",
                            editor: listaClientes
                            
                        },
                        {
                            field: "idtotem",
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
                            field: "quantidade",
                            title: "Qtd",
                            filterable: true,
                            width: "100px"
                        },
                        {
                            field: "valunit",
                            title: "Val Unitário",
                            format: "{0:c}",
                            filterable: true,
                            width: "100px"
                        },
                        {
                            field: "valtotal",
                            title: "Val Total",
                            format: "{0:c}",
                            filterable: true,
                            width: "100px",
                           
                        },

                        // {
                        //     field: "fechamento",
                        //         title: "Fechamento",
                        //             filterable: true,
                        //                 width: "100px"
                        // },
                        {
                            field: "datacriacao",
                            title: "Data Criação",
                            width: "160px",
                            format: "{0:dd/MM/yyyy}",
                            filterable: {
                                cell: {
                                    template: betweenFilter
                                }
                            }
                        },
                        // {
                        //     field: "created_at",
                        //     title: "Data Criação",
                        //     width: "160px",
                        //     format: "{0:dd/MM/yyyy}",
                        //     filterable: {
                        //         cell: {
                        //             template: betweenFilter
                        //         }
                        //     }
                        // },

                        {
                            command: [{
                                    name: "edit",
                                    text: "Editar"
                                },
                                @can('criativo-delete') {
                                    name: "Excluir",
                                    click: function(e) {
                                        e.preventDefault();
                                        var tr = $(e.target).closest(
                                            "tr"); // get the current table row (tr)
                                        var data = this.dataItem(tr);
                                        // window.location.href = location.href + '/' + data.id;

                                        Swal.fire({
                                            title: 'Tem certeza que deseja remover este criativo?',
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
                                                var url = "{{ route('excluicriativo') }}";
                                                var params = 'id=' + data.id + '&cliente=' + data.cliente + "&acesso=" + <?php if (Auth::user()->id) : echo Auth::user()->id; endif; ?> + '&totem=' + data.idtotem;

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
                                                                    'Criativo removido!',
                                                                    'É possível ver esta ação no menu Criativo -> Histórico.',
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
                    dataBound: function(e) {
                        // get the index of the UnitsInStock cell
                        var columns = e.sender.columns;
                        var columnIndex = this.wrapper.find(".k-grid-header [data-field=" + "tipocriativo" +
                            "]").index();

                        // iterate the table rows and apply custom cell styling
                        var rows = e.sender.tbody.children();
                        for (var j = 0; j < rows.length; j++) {
                            var row = $(rows[j]);
                            var dataItem = e.sender.dataItem(row);
                            var units = dataItem.get("tipocriativo");

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
                        text: "Cartelado",
                        value: 'Cartelado'
                    },
                    {
                        text: "Animado",
                        value: 'Animado'
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
                @include('layouts/filtradatadefault')    
                @include('layouts/filtraitenstabela')

            // function customBoolEditor(container, options) {
            //     $('<input class="k-checkbox" type="checkbox" name="Discontinued" data-type="boolean" data-bind="checked:Discontinued">').appendTo(container);
            // }
        </script>
    </div>
</div>

@elsecan('criativo-list')
<h1>Acesso Não Autorizado</h1>
@endcan



@endsection