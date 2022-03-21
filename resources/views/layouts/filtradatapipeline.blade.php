function betweenFilter(args) {
    var filterCell = args.element.parents(".k-filtercell");

    filterCell.empty();
    filterCell.html('<label style="width: 0px;">De: <input class="start-date" style="width: 130px;"/></label>' + '<label class="pt-4"> <br><br> Até: ' + '<input  class="end-date" style="width: 130px;"/></label>');

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
    filterCell.html('<label style="width: 0px;">De: <input class="start-dateEncerramento" style="width: 130px;"/></label><label class="pt-4"> <br><br> Até: ' + '<input  class="end-dateEncerramento" style="width: 130px;"/></label>');

    $(".start-dateEncerramento", filterCell).kendoDatePicker({
        change: function (e) {
            var startDateEncerramento = e.sender.value(),
                endDateEncerramento = $("input.end-dateEncerramento", filterCell).data("kendoDatePicker").value(),
                dataSource = $("#grid").data("kendoGrid").dataSource;

            if (startDateEncerramento & endDateEncerramento) {
                var filter = { logic: "and", filters: [] };
                filter.filters.push({ field: "datafinal", operator: "gte", value: startDateEncerramento });
                filter.filters.push({ field: "datafinal", operator: "lte", value: endDateEncerramento });
                dataSource.filter(filter);
            }
        }
    });
    $(".end-dateEncerramento", filterCell).kendoDatePicker({
        change: function (e) {
            var startDateEncerramento = $("input.start-dateEncerramento", filterCell).data("kendoDatePicker").value(),
                endDateEncerramento = e.sender.value(),
                dataSource = $("#grid").data("kendoGrid").dataSource;

            if (startDateEncerramento & endDateEncerramento) {
                var filter = { logic: "and", filters: [] };
                filter.filters.push({ field: "datafinal", operator: "gte", value: startDateEncerramento });
                filter.filters.push({ field: "datafinal", operator: "lte", value: endDateEncerramento });
                dataSource.filter(filter);
            }
        }
    });

}