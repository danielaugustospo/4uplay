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
