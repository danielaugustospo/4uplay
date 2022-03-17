<head>
    <meta charset="utf-8">
    <title>Financeiro</title>
</head>
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

@can('pipeline-list')


<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<label class="d-flex justify-content-center" style="color:red;" for="">Período Selecionado: @php echo date("d/m/Y", strtotime($dtinicial)) . " até " . date("d/m/Y", strtotime($dtfinal)); @endphp <a href="{{ route('financeiro2') }}" data-toggle="modal" data-target="#exampleModal">&nbsp;Selecionar outro período</a></label>
<div class="container p-2" style="background-color:#b0b0b0; ">
    <h2 class="pt-2 pb-2 text-center" style="font-family: system-ui;"><b> Relatório Financeiro</b></h2>

    <div id="example">
        <div id="grid"></div>

        <script>

            $(document).ready(function() {
                dataSource = new kendo.data.DataSource({
                    transport: {
                        read: {
                            // url: "{{ route('relatoriofinanceiro') }}?acesso="<?php if (Auth::user()->id) : echo '+ ' . Auth::user()->id; endif; ?>,
                            url: "{{ route('relatoriofinanceiro') }}?acesso=@if(Auth::user()->id){{Auth::user()->id}}@endif&dtinicial={{$dtinicial}}&dtfinal={{$dtfinal}}",
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
                                mensalidade: {
                                    type: "number",
                                    validation: {
                                        required: true,
                                        min: 1
                                    }
                                },
                                royalties: {
                                    type: "number"
                                },
                                valorcriativo: {
                                    type: "number",
                                    validation: {
                                        required: true,
                                        min: 1
                                    }
                                },
                                // periodo: {
                                //     type: "string"
                                // },

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
                        {
                            field: "name",
                            title: "Licenciado",
                            format: "{0:c}",
                            filterable: true,
                            width: "100px"
                        },
                        {
                            field: "mensalidade",
                            title: "Mensalidade",
                            format: "{0:c}",
                            filterable: true,
                            width: "100px"
                        },
                        {
                            field: "royalties",
                            title: "Royalties",
                            width: "100px",
                            format: "{0:c}",
                        },

                        {
                            field: "valorcriativo",
                            title: "Valor Criativo",
                            format: "{0:c}",
                            filterable: true,
                            width: "100px"
                        },
                        // {
                        //     field: "datacriativo",
                        //     title: "Período",
                        //     width: "100px"
                        // },
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

@elsecan('pipeline-list')
<h1>Acesso Não Autorizado</h1>
@endcan



@endsection