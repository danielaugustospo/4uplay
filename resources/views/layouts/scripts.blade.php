<script src="https://kendo.cdn.telerik.com/2021.3.1207/js/jquery.min.js"></script>              
<script src="{{ asset('js/kendogrid/kendo.all.min.js') }}" defer></script>

{{-- <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2022.1.301/styles/kendo.default-main.min.css" defer /> --}}

{{-- <script src="https://kendo.cdn.telerik.com/2022.1.301/js/jquery.min.js" defer></script> --}}
{{-- <script src="https://kendo.cdn.telerik.com/2022.1.301/js/kendo.all.min.js" defer></script> --}}
{{-- <script src="https://kendo.cdn.telerik.com/2022.1.301/js/jszip.min.js" defer></script> --}}
<script src="{{ asset('js/kendogrid/kendo-messages_pt-br.js') }}" defer></script>
<script src="{{ asset('js/kendogrid/kendo.culture.pt-BR.min.js') }}" defer></script>



<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.4.0/jszip.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.1/dist/sweetalert2.all.min.js" integrity="sha256-x7Yk56ZYq7Z6MPePNSTZQn42lokx3xDNDGLhwHUZa7M=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" defer></script>
<script src="{{ asset('js/scripts/util.js') }}" defer></script>

<script>
    function getDomain() {
       return document.querySelector('base').href;
   }       
</script>

<script type="x/kendo-template" id="page-template">
  
  <div class="page-template">
    <div class="header">
      <div style="float: right">Página #: pageNum # de #: totalPages #</div>
      <img src="{{ env('ASSET_URL') }}img/02.01_logo_top_4uplay.png" width="80" alt="" srcset="">
      Relatório de #: document.title # 
    </div>
    
    <div class="watermark">4UPLAY</div>
    <div class="footer">
      Página #: pageNum # de #: totalPages #
    </div>
  </div>
</script>