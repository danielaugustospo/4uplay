function listaLicenciadoEClientes(container, options) {
                
    @can("$permissaocriacao")
        var licenciadoOuCliente = 'name' ;
        var rota = "{{ route('listausuarios') }}" ;
    @else
        var licenciadoOuCliente = 'c_nome';
        var rota = "{{ route('listaclientelicenciado') }}" ;
    @endcan
    $('<input required name="' + options.field + '"/>')
    .appendTo(container)
    .kendoDropDownList({
        // dataSource: data,
        dataTextField:  licenciadoOuCliente,
        dataValueField: 'id',
        filter: "contains",
        dataSource: {
            transport: {
                read: {
                    dataType: "json",
                    url: rota,
                    data:  {
                        acesso: <?php if (Auth::user()->id) : echo Auth::user()->id; endif; ?>,
                    }
                }
            }
        }
    });
}

function listaClientes(container, options) {
                

        var licenciadoOuCliente = 'c_nome';
        var rota = "{{ route('listaclientelicenciado') }}" ;
    $('<input required name="' + options.field + '"/>')
    .appendTo(container)
    .kendoDropDownList({
        // dataSource: data,
        dataTextField:  licenciadoOuCliente,
        dataValueField: 'id',
        filter: "contains",
        dataSource: {
            transport: {
                read: {
                    dataType: "json",
                    url: rota,
                    data:  {
                        acesso: <?php if (Auth::user()->id) : echo Auth::user()->id; endif; ?>,
                    }
                }
            }
        }
    });
}
function listaTotem(container, options) {
                

            var licenciadoOuCliente = 'c_nome';
            var rota = "{{ route('listatotem') }}" ;
            $('<input required name="' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                // dataSource: data,
                dataTextField:  'n_serie',
                dataValueField: 'id',
                filter: "contains",
                dataSource: {
                    transport: {
                        read: {
                            dataType: "json",
                            url: rota,
                            data:  {
                                acesso: <?php if (Auth::user()->id) : echo Auth::user()->id; endif; ?>,
                            }
                        }
                    }
                }
            });
        }

        