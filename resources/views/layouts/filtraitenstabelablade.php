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
                filter.filters.push({ field: "datainicial", operator: "gte", value: startDate });
                filter.filters.push({ field: "datainicial", operator: "lte", value: endDate });
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
                filter.filters.push({ field: "datainicial", operator: "gte", value: startDate });
                filter.filters.push({ field: "datainicial", operator: "lte", value: endDate });
                dataSource.filter(filter);
            }
        }
    });

}


function betweenFilterLocal(args) {
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
                filter.filters.push({ field: "datafinal", operator: "gte", value: startDate });
                filter.filters.push({ field: "datafinal", operator: "lte", value: endDate });
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
                filter.filters.push({ field: "datafinal", operator: "gte", value: startDate });
                filter.filters.push({ field: "datafinal", operator: "lte", value: endDate });
                dataSource.filter(filter);
            }
        }
    });

}