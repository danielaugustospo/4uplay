<style>
  /*
      Use the DejaVu Sans font for display and embedding in the PDF file.
      The standard PDF fonts have no support for Unicode characters.
  */
  .k-grid, .k-column-title, td, th, span {
      font-family: "DejaVu Sans", "Arial", sans-serif;
      font-size: 10px;
  }



    .k-item, .k-pager-wrap, .k-grid-pager, .k-widget, .k-floatwrap, .k-grid-header .k-header>.k-link {
        font-size: 10px;
    }

    /* Page Template for the exported PDF */
    .page-template {
      font-family: "DejaVu Sans", "Arial", sans-serif;
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      font-size: 8px;

    }

    .page-template .header {
      position: absolute;
      top: 30px;
      left: 30px;
      right: 30px;
      border-bottom: 1px solid #888;
      color: #888;
      font-size: 11px;
    }
    .page-template .footer {
      position: absolute;
      bottom: 30px;
      left: 30px;
      right: 30px;
      border-top: 1px solid #888;
      text-align: center;
      color: #888;
    }
    .watermark {
      font-weight: bold;
      font-size: 400%;
      text-align: center;
      margin-top: 30%;
      color: #aaaaaa;
      opacity: 0.1;
      transform: rotate(-35deg) scale(1.7, 1.5);
    }

    /* Content styling */
    .customer-photo {
      display: inline-block;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background-size: 32px 35px;
      background-position: center center;
      vertical-align: middle;
      line-height: 32px;
      box-shadow: inset 0 0 1px #999, inset 0 0 10px rgba(0,0,0,.2);
      margin-left: 5px;
    }
    kendo-pdf-document .customer-photo {
      border: 1px solid #dedede;
    }
    .customer-name {
      display: inline-block;
      vertical-align: middle;
      line-height: 32px;
      padding-left: 3px;
    }
    
      /*
        The example loads the DejaVu Sans from the Kendo UI CDN.
        Other fonts have to be hosted from your application.
        The official site of the Deja Vu Fonts project is
        https://dejavu-fonts.github.io/.
      */
      @font-face {
        font-family: "DejaVu Sans";
        src: url("https://kendo.cdn.telerik.com/2022.1.301/styles/fonts/DejaVu/DejaVuSans.ttf") format("truetype");
      }

      @font-face {
        font-family: "DejaVu Sans";
        font-weight: bold;
        src: url("https://kendo.cdn.telerik.com/2022.1.301/styles/fonts/DejaVu/DejaVuSans-Bold.ttf") format("truetype");
      }

      @font-face {
        font-family: "DejaVu Sans";
        font-style: italic;
        src: url("https://kendo.cdn.telerik.com/2022.1.301/styles/fonts/DejaVu/DejaVuSans-Oblique.ttf") format("truetype");
      }

      @font-face {
        font-family: "DejaVu Sans";
        font-weight: bold;
        font-style: italic;
        src: url("https://kendo.cdn.telerik.com/2022.1.301/styles/fonts/DejaVu/DejaVuSans-Oblique.ttf") format("truetype");
      }

      @font-face {
        font-family: "WebComponentsIcons";
        src: url("https://kendo.cdn.telerik.com/2022.1.301/styles/fonts/glyphs/WebComponentsIcons.ttf") format("truetype");

      }
      </style>