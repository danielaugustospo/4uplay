$("#grid").kendoGrid({
    toolbar: [
    @isset($permissaocriacao)
    @if($permissaocriacao == '1')    
    {
        name: "create",
        text: "Novo"
    },
    @endif
    @endisset
    {
        name: "excel",
        text: "Excel"
    }, {
        name: "pdf",
        text: "PDF"
    }],

    excel: {
        fileName: "Relatório de " + document.title + ".xlsx",

    },
    excelExport: function(e) {

        var sheet = e.workbook.sheets[0];
        sheet.frozenRows = 1;
        sheet.mergedCells = ["A1:H1"];
        sheet.name = "Relatorio de " + document.title + " -  4UPLAY";

        var myHeaders = [{
            value: "Relatório de " + document.title,
            textAlign: "center",
            background: "black",
            color: "#ffffff"
        }];

        sheet.rows.splice(0, 0, {
            cells: myHeaders,
            type: "header",
            height: 70
        });
    },

    pdf: {
        fileName: "Relatório de " + document.title + ".pdf",

        allPages: true,
        avoidLinks: true,
        paperSize: "A4",
        margin: {
            top: "2cm",
            left: "1cm",
            right: "1cm",
            bottom: "1cm"
        },
        landscape: true,
        repeatHeaders: true,
        template: $("#page-template").html(),
        scale: 0.8
    },


    dataSource: dataSource,
    // pageable: true,
    // filterable: true,
    sortable: true,
    resizable: true,
    responsible: true,
    // scrollable: true,
    reorderable: true,
    width: 'auto',
    columnMenu: true,

    filterable: {
        extra: false,
        mode: "row"
    },
    pageable: {
        pageSizes: [5, 10, 15, 20, 50, 100, 200, "Todos"],
        numeric: false
    },