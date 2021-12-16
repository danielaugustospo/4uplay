@extends('layouts.app')
@section('content')
    <style>

        .critical {
            background-color: rgb(243, 128, 128) !important;
        }

        .warning {
            background-color: rgb(233, 194, 136) !important;
        }

        .ok {
            background-color: rgb(217, 236, 245) !important;
        }

    </style>
    
@can('pipeline-list')

    <div class="container mt-1 p-2" style="background-color:#b0b0b0; ">
        <h2>Pipeline</h2>

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
                                url: "{{ route('listapipeline') }}",
                                dataType: "json"
                            },
                            update: {
                                url: "{{ route('atualizapipeline') }}",
                                dataType: "json",
                                type: "post"
                            },
                            destroy: {
                                url: "{{ route('excluipipeline') }}",
                                dataType: "json"
                            },

                            create: {
                                url: "{{ route('criapipeline') }}",
                                dataType: "json"
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
                                            min: 1
                                        }
                                    },
                                    fechamento: {
                                        type: "string"
                                    },
                                    negociacao: {
                                        type: "string"
                                    },
                                    created_at: {
                                        type: "date",
                                        editable: false
                                    },
                                }
                            }
                        },

                    });

                    $("#grid").kendoGrid({
                        toolbar: [{
                            name: "create",
                            text: "Novo"
                        }, {
                            name: "excel",
                            text: "Excel"
                        }, {
                            name: "pdf",
                            text: "PDF"
                        }],

                        excel: {
                            fileName: "Relatório de " + document.title + ".xlsx",

                        },
                        excelExport: function(e) {

                            var sheet = e.workbook.sheets[0];
                            sheet.frozenRows = 1;
                            sheet.mergedCells = ["A1:H1"];
                            sheet.name = "Relatorio de " + document.title + " -  4UPLAY";

                            var myHeaders = [{
                                value: "Relatório de " + document.title,
                                textAlign: "center",
                                background: "black",
                                color: "#ffffff"
                            }];

                            sheet.rows.splice(0, 0, {
                                cells: myHeaders,
                                type: "header",
                                height: 70
                            });
                        },

                        pdf: {
                            fileName: "Relatório de " + document.title + ".pdf",

                            allPages: true,
                            avoidLinks: true,
                            paperSize: "A4",
                            margin: {
                                top: "2cm",
                                left: "1cm",
                                right: "1cm",
                                bottom: "1cm"
                            },
                            landscape: true,
                            repeatHeaders: true,
                            template: $("#page-template").html(),
                            scale: 0.8
                        },


                        dataSource: dataSource,
                        pageable: true,
                        filterable: true,
                        sortable: true,
                        resizable: true,
                        // responsible: true,
                        pageable: {
                            pageSizes: [5, 10, 15, 20, 50, 100, 200, "Todos"],
                            numeric: false
                        },
                        columns: [{
                                field: "cliente",
                                title: "Cliente",
                                filterable: true,
                                width: "100px"
                            },
                            {
                                field: "qualificacao",
                                title: "Qualificação",
                                filterable: true,
                                width: "100px",
                                editor: categoryDropDownEditor
                            },
                            {
                                field: "proposta",
                                title: "Proposta",
                                format: "{0:c}",
                                filterable: true,
                                width: "100px"
                            },
                            {
                                field: "negociacao",
                                title: "Negociação",
                                filterable: true,
                                width: "100px"
                            },
                            {
                                field: "fechamento",
                                title: "Fechamento",
                                filterable: true,
                                width: "100px"
                            },
                            {
                                field: "created_at",
                                title: "Data Criação",
                                width: "120px",
                                format: "{0:dd/MM/yyyy}"
                            },

                            {
                                command: [{
                                        name: "edit",
                                        text: "Editar"
                                    },
                                    {
                                        name: "Excluir",
                                        click: function(e) {
                                            e.preventDefault();
                                            var tr = $(e.target).closest(
                                                "tr"); // get the current table row (tr)
                                            var data = this.dataItem(tr);
                                            // window.location.href = location.href + '/' + data.id;

                                            if (window.confirm(
                                                    "Tem certeza que deseja realizar esta operação?")) {

                                                var http = new XMLHttpRequest();
                                                var url = "{{ route('excluipipeline') }}";
                                                var params = 'id=' + data.id + '&cliente=' + data
                                                    .cliente;
                                                http.open('POST', url, true);

                                                //Send the proper header information along with the request
                                                http.setRequestHeader('Content-type',
                                                    'application/x-www-form-urlencoded');

                                                http.onreadystatechange =
                                                    function() { //Call a function when the state changes.
                                                        if (http.readyState != 4 && http.status !=
                                                            200) {
                                                            alert(
                                                                "Ocorreu um erro ao executar o procedimento"
                                                            );
                                                        } else {
                                                            recarrega();
                                                        }
                                                    }
                                                http.send(params);

                                            } else {
                                                recarrega();
                                            }
                                        }
                                    }
                                ],
                                width: 120,
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
                                var units = dataItem.get("qualificacao");

                                var cell = row.children().eq(columnIndex);
                                $(rows[j]).addClass(getUnitsInStockClass(units));
                            }
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

                // function customBoolEditor(container, options) {
                //     $('<input class="k-checkbox" type="checkbox" name="Discontinued" data-type="boolean" data-bind="checked:Discontinued">').appendTo(container);
                // }
            </script>
        </div>
    </div>
      
@elsecan('pipeline-list')
        <h1>Acesso Não Autorizado</h1>
@endcan



@endsection
