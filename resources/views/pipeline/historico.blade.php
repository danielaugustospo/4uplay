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

        td{
            font-size: 13;
        }
        .k-grid-header .k-header>.k-link {
            font-size: 13;
        }

    </style>
    
@can('pipeline-historico')


    <div class="container mt-1 p-2" style="background-color:#b0b0b0; ">
        <h2>Histórico de Pipeline</h2>

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
                                title: "Dt Inicial",
                                width: "120px",
                                format: "{0:dd/MM/yyyy}"
                            },
                            {
                                field: "h_dtoperacao",
                                title: "Dt Operação",
                                width: "160px",
                                format: "{0:dd/MM/yyyy}",
                                filterable: {
                                cell: {
                                    template: betweenFilter
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

                function betweenFilter(args) {
    var filterCell = args.element.parents(".k-filtercell");

    filterCell.empty();
    filterCell.html('<label style="width: 0px;">De: <input class="start-date"/></label>' + '<label class="pt-2"> <br><br> Até: ' + '<input  class="end-date"/></label>');

    $(".start-date", filterCell).kendoDatePicker({
        change: function (e) {
            var startDate = e.sender.value(),
                endDate = $("input.end-date", filterCell).data("kendoDatePicker").value(),
                dataSource = $("#grid").data("kendoGrid").dataSource;

            if (startDate & endDate) {
                var filter = { logic: "and", filters: [] };
                filter.filters.push({ field: "h_dtoperacao", operator: "gte", value: startDate });
                filter.filters.push({ field: "h_dtoperacao", operator: "lte", value: endDate });
                dataSource.filter(filter);
            }
        }
    });
    $(".end-date", filterCell).kendoDatePicker({
        change: function (e) {
            var startDate = $("input.start-date", filterCell).data("kendoDatePicker").value(),
                endDate = e.sender.value(),
                dataSource = $("#grid").data("kendoGrid").dataSource;

            if (startDate & endDate) {
                var filter = { logic: "and", filters: [] };
                filter.filters.push({ field: "h_dtoperacao", operator: "gte", value: startDate });
                filter.filters.push({ field: "h_dtoperacao", operator: "lte", value: endDate });
                dataSource.filter(filter);
            }
        }
    });

}

            </script>
        </div>
    </div>
      
@elsecan('pipeline-historico')
        <h1>Acesso Não Autorizado</h1>
@endcan



@endsection
