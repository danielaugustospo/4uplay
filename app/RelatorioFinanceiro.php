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

    public function consultaRelatorioFinanceiro()
    {   

        $stringQuery = "SELECT 	u.name, 
		criacao.datacriativo, 
		mensalidade.valor mensalidade, 
		SUM(criacao.valtotal) as valorcriativo,  
		SUM(criacao.valcriativo) as valcriativonovo,
		criacao.datacriativo, 
		-- criacao.created_at,
		
        (mensalidade.valor * 100) / criacao.valtotal as royalties,
        SUM((p.fechamento) * 0.04) as fechamento
                
        
        from 
        
        (SELECT m.ano ano, m.valor from mensalidade m) as mensalidade,
        (select idlicenciado, valtotal, 
        (valtotal * 0.7) as valcriativo , 
        created_at, DATE_FORMAT(created_at, '%m-%Y') as datacriativo from criativo) as criacao
        left join users u on criacao.idlicenciado = u.id 
        left join pipeline p on  criacao.idlicenciado = p.idautor

        
        
        where 
        criacao.datacriativo   	like CONCAT('%', mensalidade.ano)
        and p.created_at   		like CONCAT(mensalidade.ano , '%')
             
 		group by u.name, datacriativo 
        ";

        return $stringQuery;
    }

}