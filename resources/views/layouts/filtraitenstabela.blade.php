function qualificacaoFilter(args) {
    var filterCell = args.element.parents(".k-filtercell");
    filterCell.empty();
    filterCell.html('<input id="dropdownlist" style="width: 60px;" />' );

    $("#dropdownlist", filterCell).kendoDropDownList({
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
