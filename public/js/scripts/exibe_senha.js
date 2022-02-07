$(document).ready(function() {
    $("#mostra_senha a").on('click', function(event) {
        event.preventDefault();
        if ($('#mostra_senha input').attr("type") == "text") {
            $('#mostra_senha input').attr('type', 'password');
            $('#mostra_senha i').addClass("fa-eye-slash");
            $('#mostra_senha i').removeClass("fa-eye");
        } else if ($('#mostra_senha input').attr("type") == "password") {
            $('#mostra_senha input').attr('type', 'text');
            $('#mostra_senha i').removeClass("fa-eye-slash");
            $('#mostra_senha i').addClass("fa-eye");
        }
    });
    $("#mostra_senha_confirmacao a").on('click', function(event) {
        event.preventDefault();
        if ($('#mostra_senha_confirmacao input').attr("type") == "text") {
            $('#mostra_senha_confirmacao input').attr('type', 'password');
            $('#mostra_senha_confirmacao i').addClass("fa-eye-slash");
            $('#mostra_senha_confirmacao i').removeClass("fa-eye");
        } else if ($('#mostra_senha_confirmacao input').attr("type") == "password") {
            $('#mostra_senha_confirmacao input').attr('type', 'text');
            $('#mostra_senha_confirmacao i').removeClass("fa-eye-slash");
            $('#mostra_senha_confirmacao i').addClass("fa-eye");
        }
    });
});
