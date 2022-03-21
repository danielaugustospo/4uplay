@if(isset($periodomes))
function filtraMeses(args) {
    var filterCell = args.element.parents(".k-filtercell");

    filterCell.empty();
    filterCell.html('<label style="width: 0px;">De: <input type="month" class="start-date"/></label>' + '<label class="pt-4"> <br><br> Até: ' + '<input type="month" class="end-date"/></label>');

    $(".start-date", filterCell).kendoDatePicker({
        change: function (e) {
            var startDate = e.sender.value(),
                endDate = $("input.end-date", filterCell).data("kendoDatePicker").value(),
                dataSource = $("#grid").data("kendoGrid").dataSource;

            if (startDate & endDate) {
                var filter = { logic: "and", filters: [] };
                filter.filters.push({ field: "{{$periodomes}}", operator: "gte", value: startDate });
                filter.filters.push({ field: "{{$periodomes}}", operator: "lte", value: endDate });
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
                filter.filters.push({ field: "{{$periodomes}}", operator: "gte", value: startDate });
                filter.filters.push({ field: "{{$periodomes}}", operator: "lte", value: endDate });
                dataSource.filter(filter);
            }
        }
    });
    $("#dateinput").kendoDateInput({
        format: "yyyy/MM/dd"
    });
}
@endif