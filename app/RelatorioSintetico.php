<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelatorioSintetico extends Model
{
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'name', 'detail'
    ];

    public function consultaRelatorioSintetico($complemento)
    {   

        $stringQuery = "SELECT 
        COUNT(criacao.cliente) as qtdeclientesfechados, 
        u.name as licenciado,
        
        (mensalidade.valor) + 
        (SUM(criacao.valtotal) * ((mensalidade.valor * 100) / SUM(criacao.valtotal))) as valtotal, 
        
        SUM(criacao.quantidade) as criativosfechados,
        
        (mensalidade.valor) + (SUM(criacao.valtotal) * ((mensalidade.valor * 100) / SUM(criacao.valtotal)))
        + SUM(criacao.valtotal) as valfinal, 
        criacao.datacriativo
        
        FROM 
        
        (SELECT m.ano ano, m.valor FROM mensalidade m) as mensalidade,
        (SELECT cliente, valtotal, quantidade,  DATE_FORMAT(created_at, '%m-%Y') as datacriativo FROM criativo) as criacao
        left join users u on criacao.cliente = u.id 
        
        
        where criacao.datacriativo like CONCAT('%', ano)
        and criacao.cliente = u.id  $complemento
        group by criacao.datacriativo      
        ";

        return $stringQuery;
    }

}