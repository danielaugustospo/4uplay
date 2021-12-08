@extends('layouts.app')
@section('content')

<div class="container mt-3 p-5" style="background-color:#b0b0b0; ">
<h2 class="text-center">Relatório Financeiro</h2>

<table class="table">
  <thead class="thead-dark">
    <tr class="col-sm-10">
      <th scope="col">Valor mensalidade</th>
      <th scope="col">Valor dos Royalties</th>
      <th scope="col">Valor do Criativo</th>
    </tr>
  </thead>
  <tbody>
      <tr><th scope="row"> R$45412,17</th><td>  R$1430,85</td>   <td>R$52,41</td>  </tr>
      <tr><th scope="row"> R$55412,29</th><td>  R$246,85</td>   <td>R$45,21</td> </tr>
      <tr><th scope="row"> R$55412,94</th><td>  R$1242,37</td> <td>R$454,52</td> </tr>
      <tr><th scope="row"> R$565412,13</th><td>  R$118,20</td>   <td>R$545,22</td> </tr>
      <tr><th scope="row"> R$55412,85</th><td>  R$890,78</td>  <td>R$5945,21</td>   </tr>

  </tbody>
</table>
</div>
@endsection
