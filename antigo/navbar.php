<?php
include('imports.php');
?>
<div class="justify-content-center">
<nav class="navbar navbar-expand-lg navbar-light bg-light " style="background-color: #8A2BE2 !important;">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <img src="materiais/img/02.01_logo_top_4uplay.png" width="80" alt="" srcset="">
      <div class="col-sm-4"></div>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="cadastro.php" style="color:white !important;">Cadastro</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="pipeline.php" style="color:white !important;">Pipeline</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="criativo.php" style="color:white !important;">Criativo</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:white !important;">
          Relatório
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="relatoriosintetico.php">Sintético</a>
          <a class="dropdown-item" href="relatoriorepasse.php">Financeiro</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
</div>

