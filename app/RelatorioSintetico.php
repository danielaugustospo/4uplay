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

    public function consultaRelatorioSintetico($complemento, $dtinicial, $dtfinal)
    {   

        $stringQuery = "SELECT u.name as licenciado, p.qtdeclientesfechados, p.valtotal as pvaltotal, c.valtotal as cvaltotal, c.criativosfechados, p.idlicenciado, c.idlicenciado, p.valtotal as valorpipe, c.valtotal as valcriativo, p.datapipeline, c.datacriativo
        from users u
        
         LEFT JOIN  (SELECT COUNT(cliente) as qtdeclientesfechados, cliente, idautor as idlicenciado, SUM(fechamento) as valtotal, DATE_FORMAT(pipeline.created_at, '%m-%Y') as datapipeline FROM pipeline WHERE (pipeline.datainicial BETWEEN '$dtinicial' AND '$dtfinal') and (pipeline.fechamento > '0.00') and (excluidopipeline = 0) group by idlicenciado) as p on u.id = p.idlicenciado 
         LEFT JOIN (SELECT cliente, idlicenciado, SUM(valtotal) as valtotal, sum(quantidade) as criativosfechados,  DATE_FORMAT(criativo.created_at, '%m-%Y') as datacriativo FROM criativo WHERE (criativo.datacriacao BETWEEN '$dtinicial' AND '$dtfinal') and (excluidocriativo = 0) group by idlicenciado) as c on u.id = c.idlicenciado 
        where u.id = p.idlicenciado or u.id = c.idlicenciado
        $complemento
        group by u.name";

        // var_dump($stringQuery);
        // exit;
        return $stringQuery;
    }

}