function qualificacaoFilter(args) {
    var filterCell = args.element.parents(".k-filtercell");
    filterCell.empty();
    filterCell.html('<input id="dropdownlist" style="width: 60px;" />' );

    $("#dropdownlist", filterCell).kendoDropDownList({
        filter: "contains",
        dataSource: ["Todos", "Morno", "Frio", "Quente"],
 
        change: function (e) {
            dropdownlist = $("#dropdownlist", filterCell).data("kendoDropDownList")._old,
            
            
            dataSource = $("#grid").data("kendoGrid").dataSource,
            
            filter = { logic: "eq", filters: [] };
            if(dropdownlist == 'Todos'){
                filter.filters.push({ field: "qualificacao", operator: "isnotnull", value: dropdownlist });
            }else{
                filter.filters.push({ field: "qualificacao", operator: "eq", value: dropdownlist });
            }
            dataSource.filter(filter);   
        }
      });
}

function filtraClientePipeline(args) {
    var filterCell = args.element.parents(".k-filtercell");
    filterCell.empty();
    filterCell.html('<input id="dropdownlist" style="width: 60px;" />' );

    var rotaClientePipeline = "{{ route('listaclientelicenciado') }}" ;
    var propCliente = 'c_nome';


    $("#dropdownlist", filterCell).kendoDropDownList({
        // dataSource: data,
        filter: "contains",
        optionLabel: "Todos",
        dataTextField:  propCliente,
        dataValueField: propCliente,
        dataSource: {
            transport: {
                read: {
                    dataType: "json",
                    url: rotaClientePipeline,
                    data:  {
                        acesso: <?php if (Auth::user()->id) : echo Auth::user()->id; endif; ?>,
                    }
                }
            }
        },

        change: function (e) {
            dropdownlist = $("#dropdownlist", filterCell).data("kendoDropDownList")._old,
            console.log(dropdownlist);
            
            dataSource = $("#grid").data("kendoGrid").dataSource,
            
            filter = { logic: "eq", filters: [] };
            if(dropdownlist == ''){
                filter.filters.push({ field: "cliente", operator: "isnotnull", value: dropdownlist });
            }else{
                filter.filters.push({ field: "cliente", operator: "eq", value: dropdownlist });
            }
            dataSource.filter(filter);   
        }
      });
}


function filtraTotemPipeline(args) {
    var filterCell = args.element.parents(".k-filtercell");
    filterCell.empty();
    filterCell.html('<input id="dropdownlist" style="width: 60px;" />' );

    var rotaTotem = "{{ route('listatotem') }}" ;


    $("#dropdownlist", filterCell).kendoDropDownList({
        // dataSource: data,
        filter: "contains",
        dataTextField:  'n_serie',
        dataValueField: 'n_serie',
        optionLabel: "Todos",
        dataSource: {
            transport: {
                read: {
                    dataType: "json",
                    url: rotaTotem,
                    data:  {
                        acesso: <?php if (Auth::user()->id) : echo Auth::user()->id; endif; ?>,
                    }
                }
            }
        },

        change: function (e) {
            dropdownlist = $("#dropdownlist", filterCell).data("kendoDropDownList")._old,
            
            
            dataSource = $("#grid").data("kendoGrid").dataSource,
            
            filter = { logic: "eq", filters: [] };
            if(dropdownlist == ''){
                filter.filters.push({ field: "n_serie", operator: "isnotnull", value: dropdownlist });
            }else{
                filter.filters.push({ field: "n_serie", operator: "eq", value: dropdownlist });
            }
            dataSource.filter(filter);   
        }
      });
}