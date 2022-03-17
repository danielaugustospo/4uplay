function listaTotem(container, options) {
                

        var licenciadoOuCliente = 'c_nome';
        var rota = "{{ route('listatotem') }}" ;
    $('<input required name="' + options.field + '"/>')
    .appendTo(container)
    .kendoDropDownList({
        // dataSource: data,
        dataTextField:  'n_serie',
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
