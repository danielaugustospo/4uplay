@extends('layouts.app')
@section('content')

@can('relatorio-sintetico')


<div class="container p-2" style="background-color:#b0b0b0; ">
    <h2 class="pt-2 pb-2 text-center" style="font-family: system-ui;"><b> Relatório Sintético</b></h2>

    <div id="example">
        <div id="grid"></div>

        <script>

            $(document).ready(function() {
                dataSource = new kendo.data.DataSource({
                    transport: {
                        read: {
                            url: "{{ route('relatoriosintetico') }}?acesso="
                            <?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id;
                            endif; ?>,
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
                              qtdeclientesfechados: {
                                    type: "string"
                                },
                                valtotal: {
                                    type: "number",
                                    validation: {
                                        required: true,
                                        min: 1
                                    }
                                },
                                criativosfechados: {
                                    type: "string"
                                },
                                valfinal: {
                                    type: "number",
                                    validation: {
                                        required: true,
                                        min: 1
                                    }
                                },
                                periodo: {
                                    type: "string"
                                },

                                // created_at: {
                                //     type: "date",
                                //     editable: false
                                // },
                            }
                        }
                    },

                });

                @include('layouts/customizacoestabela')

                    columns: [
                        @can('relatorio-financeiro')
                        {
                            field: "licenciado",
                            title: "Licenciado",
                            width: "100px"
                        },
                        @endcan
                        {
                            field: "qtdeclientesfechados",
                            title: "Qtde Clientes Fechados",
                            width: "100px"
                        },
                        {
                            field: "valtotal",
                            title: "Valor Total",
                            format: "{0:c}",
                            filterable: true,
                            width: "100px"
                        },
                        {
                            field: "criativosfechados",
                            title: "Qtde Criativos Fechados",
                            width: "100px"
                        },
                        {
                            field: "valfinal",
                            title: "Valor Final",
                            format: "{0:c}",
                            filterable: true,
                            width: "100px"
                        },
                        {
                            field: "datacriativo",
                            title: "Período",
                            width: "100px"
                        },
                    ],
                    
                    editable: "inline"
                });



                function recarrega() {
                    $('#grid').data('kendoGrid').dataSource.read();
                    $('#grid').data('kendoGrid').refresh();
                }
            });

        
        @include('layouts/filtradata')


        </script>
    </div>
</div>

@elsecan('relatorio-sintetico')
<h1>Acesso Não Autorizado</h1>
@endcan



@endsection