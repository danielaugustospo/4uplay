@extends('layouts.app')
@section('content')

<style>
  td {
    font-size: 13;
  }
  .k-grid-header .k-header>.k-link {
    font-size: 13;
  }
</style>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

    {{-- @can('usuarios-list') --}}


    <div class="pull-right">
      <a class="btn btn-success" style="background-color: #8A2BE2 !important; border-color: #8A2BE2 !important;" href="{{ route('users.create') }}"> Cadastrar Usuário</a>
    </div>
        <div class="container p-2" style="background-color:#b0b0b0; ">
            <h1 class="pt-2 pb-2 text-center" style="font-family: system-ui;"><b>Gerenciamento de Usuários</b></h1>
            <div id="example">
                <div id="grid"></div>

                <script>
                    $(document).ready(function() {
                    dataSource = new kendo.data.DataSource({
                        transport: {
                            read: {
                                url: "{{ route('apiusuarios') }}?acesso="<?php if (Auth::user()->id): echo '+ ' . Auth::user()->id; endif; ?>,
                                dataType: "json",
                            },
                            parameterMap: function(options, operation) {

                                return {
                                    models: kendo.stringify(options.models)
                                };

                            }
                        },
                        batch: true,
                        pageSize: 20,
                        schema: {
                            model: {
                                id: "id",
                                fields: {

                                    name: {
                                        type: "string"
                                    },
                                    email: {
                                        type: "string"
                                    },
                                    permissao: {
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

                    @include('layouts/customizacoestabela', ['permissaocriacao' => '0'])
                    columns: [{
                                field: "name",
                                title: "Nome",
                                filterable: true,
                                width: "100px"
                            },

                            {
                                field: "email",
                                title: "Email",
                                filterable: true,
                                width: "100px"
                            },
                            {
                                field: "permissao",
                                title: "Nível de Acesso",
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
                                        name: "view",
                                        text: "Visualizar",
                                        click: function(e) {
                                        e.preventDefault();
                                        var tr = $(e.target).closest("tr"); // get the current table row (tr)
                                        var data = this.dataItem(tr);
                                        window.location.href = location.href + '/' + data.id;
                                    }
                                  },
                                ],
                                width: 100,
                                exportable: false,
                                title: "Ações",
                            },

                        ],
                    });

                    });
                    @include('layouts/filtradata')
                </script>
            </div>
        </div>

    {{-- @elsecan('usuarios-list')
        <h1>Acesso Não Autorizado</h1>
    @endcan --}}












@endsection
