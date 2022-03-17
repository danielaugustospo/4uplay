<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelatorioFinanceiro extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'name', 'detail'
    ];

    public function consultaRelatorioFinanceiro($dtinicial, $dtfinal)
    {   

    $stringQuery = "SELECT 
    u.id, 
    u.name, 
    (mensalidade.valor * contatotens.qtd) mensalidade, 
    (dadospipeline.valtotal * 0.04) as royalties, 
    (criacao.valtotal * 0.7) as valorcriativo,  
    criacao.datacriativo,
    contatotens.h_dtassociado 
            
    FROM 
    users u
    
       LEFT JOIN (SELECT h_idtotem, h_idcliente, COUNT(DISTINCT h_idtotem ) qtd, h_dtassociado
     			FROM historico_totemcliente 
     			WHERE (h_dtassociado BETWEEN '$dtinicial' AND '$dtfinal')
     			GROUP BY h_idcliente) AS contatotens on u.id = contatotens.h_idcliente			
     		
    
     LEFT JOIN (SELECT  idlicenciado, SUM(valtotal) as valtotal, DATE_FORMAT(criativo.datacriacao, '%m-%Y') as datacriativo
     			FROM criativo
     			WHERE (criativo.datacriacao BETWEEN '$dtinicial' AND '$dtfinal')  and (excluidocriativo = 0)  GROUP BY idlicenciado) as criacao on u.id = criacao.idlicenciado
     			
     
     LEFT JOIN (SELECT COUNT(cliente) as qtdeclientesfechados, cliente, idautor as idlicenciado, SUM(fechamento) as valtotal, pipeline.datainicial 
     			FROM pipeline
     			WHERE (pipeline.datainicial BETWEEN '$dtinicial' AND '$dtfinal') and (excluidopipeline = 0) GROUP BY idlicenciado) AS dadospipeline on u.id = dadospipeline.idlicenciado,
     			(SELECT m.ano ano, m.valor from mensalidade m) as mensalidade

     WHERE 
		contatotens.h_dtassociado  like CONCAT(mensalidade.ano , '%')

	GROUP BY u.id, u.name, datacriativo";

// var_dump($stringQuery);
// exit;

     return $stringQuery;
    }

}