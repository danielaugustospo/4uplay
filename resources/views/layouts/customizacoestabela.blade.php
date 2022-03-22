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
        sheet.mergedCells = ["A1:I1"];
        sheet.name = "Relatorio de " + document.title + " -  4UPLAY";

        var myHeaders = [{
            value: "4UPLAY - Relatório de " + document.title,
            textAlign: "center",
            background: "#8A2BE2",
            color: "#FAC910"
        }];

        sheet.rows.splice(0, 0, {
            cells: myHeaders,
            type: "header",
            height: 30
        });
    },

    pdf: {
        fileName: "Rel_de_" + document.title + ".pdf",

        allPages: true,
        avoidLinks: false,
        paperSize: "A4",
        margin: {
            top: "2.5cm",
            left: "0.2cm",
            right: "0.2cm",
            bottom: "1cm"
        },
        @isset($orientacao)
            landscape: false,
        @else
            landscape: true,
        @endisset
        repeatHeaders: false,
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
        extra: true,
        mode: "row"
    },
    pageable: {
        pageSizes: [5, 10, 15, 20, 50, 100, 200, "Todos"],
        numeric: false
    },