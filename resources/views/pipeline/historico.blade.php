<head>
    <meta charset="utf-8">
    <title>Histórico Pipeline</title>
</head>
@extends('layouts.app')
@section('content')

<?php
  $dataUm   = "h_dtoperacao";
  $dataDois = "datainicial";
  $dataTres = "datafinal";
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
    
@can('pipeline-historico')


    <div class="p-1" style="background-color:#b0b0b0; ">
        <h2 class="pt-2 pb-2 text-center" style="font-family: system-ui;"><b>Histórico de Pipeline</b></h2>

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
                                url: "{{ route('listahistoricopipeline') }}?acesso=" <?php  if(Auth::user()->id): echo "+ ". Auth::user()->id; endif; ?> ,
                                dataType: "json",
                                // headers: { 'Authorization': 'Bearer {{ $_COOKIE['gerenciamento_4uplay_session'] }}' },
                            },
                            parameterMap: function(options, operation) {
                                if (operation !== "read" && options.models) {
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
                                        editable: false,
                                        nullable: true
                                    },
                                    h_cliente: {
                                        validation: {
                                            required: true
                                        }
                                    },
                                    h_qualificacao: {
                                        type: "string"
                                    },
                                    h_proposta: {
                                        type: "number",
                                        validation: {
                                            required: true,
                                            min: 1
                                        }
                                    },
                                    @can('pipeline-delete')
                                    licenciado: {
                                        type: "string",
                                        editable: false
                                    },
                                    @endcan
                                    h_fechamento: {
                                        type: "string"
                                    },
                                    h_negociacao: {
                                        type: "string"
                                    },
                                    h_tipooperacao: {
                                        type: "string"
                                    },
                                    h_dt_proposta: {
                                        type: "date",
                                        editable: false
                                    },
                                    h_dtoperacao: {
                                        type: "date",
                                        editable: false
                                    },
                                    datainicial: {
                                        type: "date",
                                        editable: false
                                    },
                                    datafinal: {
                                        type: "date",
                                        editable: false
                                    },

                                }
                            }
                        },

                    });

                    @include('layouts/customizacoestabela')

                        columns: [
                            {
                                field: "h_idpipeline",
                                title: "Id",
                                filterable: true,
                                width: "60px"
                            },
                            {
                                field: "h_cliente",
                                title: "Cliente",
                                filterable: true,
                                width: "105px"
                            },
                            {
                                field: "h_idtotem",
                                title: "Totem",
                                filterable: true,
                                width: "105px"
                            },
                            {
                                field: "h_qualificacao",
                                title: "Qualificação",
                                filterable: true,
                                width: "80px",
                                editor: categoryDropDownEditor
                            },
                            {
                                field: "h_proposta",
                                title: "Proposta",
                                format: "{0:c}",
                                filterable: true,
                                width: "100px"
                            },                            
                            @can('pipeline-delete')
                            {
                                field: "licenciado",
                                title: "Licenciado",
                                filterable: true,
                                width: "105px",
                            },
                            @endcan
                            {
                                field: "h_negociacao",
                                title: "Negociação",
                                filterable: true,
                                width: "100px"
                            },
                            {
                                field: "h_tipooperacao",
                                title: "Operação",
                                filterable: true,
                                width: "85px"
                            },
                            {
                                field: "h_fechamento",
                                title: "Fechamento",
                                filterable: true,
                                width: "100px"
                            },
                            {
                                field: "h_dt_proposta",
                                title: "Dt Proposta",
                                width: "180px",
                                format: "{0:dd/MM/yyyy}"
                            },
                            {
                                field: "h_dtoperacao",
                                title: "Dt Operação",
                                width: "180px",
                                format: "{0:dd/MM/yyyy}",
                                filterable: {
                                  cell: {
                                    template: betweenFilter
                                  }
                                },
                            },
                            {
                                field: "datainicial",
                                title: "Dt Inicial",
                                width: "180px",
                                format: "{0:dd/MM/yyyy}",
                                filterable: {
                                  cell: {
                                    template: betweenFilterDois
                                  }
                                }
                            },
                            {
                                field: "datafinal",
                                title: "Dt Final",
                                width: "160px",
                                format: "{0:dd/MM/yyyy}",
                                filterable: {
                                cell: {
                                    template: betweenFilterTres
                                  }
                                }
                            },
                            


                        ],
                        dataBound: function(e) {
                            // get the index of the UnitsInStock cell
                            var columns = e.sender.columns;
                            var columnIndex = this.wrapper.find(".k-grid-header [data-field=" + "h_qualificacao" +
                                "]").index();

                            // iterate the table rows and apply custom cell styling
                            var rows = e.sender.tbody.children();
                            for (var j = 0; j < rows.length; j++) {
                                var row = $(rows[j]);
                                var dataItem = e.sender.dataItem(row);
                                var units = dataItem.get("h_qualificacao");

                                var cell = row.children().eq(columnIndex);
                                // $(rows[j]).addClass(getUnitsInStockClass(units));
                                $(cell).addClass(getUnitsInStockClass(units));
                            }
                            $( ".critical" ).append( $( '<i class="pl-2 fas fa-fire"></i>' ) );
                            $( ".warning" ).append( $( '<i class="pl-2 fas fa-mug-hot"></i>' ) );
                            $( ".ok" ).append( $( '<i class="pl-2 fas fa-snowflake"></i>' ) );
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

                    // $('<input required name="' + options.field + '"/>')
                    //     .appendTo(container)
                    //     .kendoDropDownList({
                    //         dataSource: data,
                    //         dataTextField: "text",
                    //         dataValueField: "value"
                    //     });
                }

                // function customBoolEditor(container, options) {
                //     $('<input class="k-checkbox" type="checkbox" name="Discontinued" data-type="boolean" data-bind="checked:Discontinued">').appendTo(container);
                // }

                @include('layouts/filtradatadefault')
                



            </script>
        </div>
    </div>
      
@elsecan('pipeline-historico')
        <h1>Acesso Não Autorizado</h1>
@endcan



@endsection
