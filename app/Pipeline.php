<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Pipeline extends Model
{


    protected $table = 'pipeline';

    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'cliente',
        'qualificacao',
        'idtotem',
        'n_serie',
        'proposta',
        'fechamento',
        'negociacao',
        'idautor',
        'id_ult_alterador',
        'excluidopipeline',
        'datainicial',
        'datafinal',

    ];

    public function consultaPipeline($id)
    {   
        $string = '';
        if($id){ $string .= ' AND u.id='.$id; }else { $string .= ''; }

        $stringQuery = "SELECT p.*, u.name as 'licenciado', t.n_serie, c.c_nome as 'cliente'
        from pipeline p

        left join users u on p.idautor = u.id 
        left join clienteslicenciado c on p.cliente = `c`.`id`
        left join totem t on p.idtotem = `t`.`id`

        WHERE excluidopipeline = 0 " . $string ." GROUP BY id";

        return $stringQuery;
    }

    public function consultaHistoricoPipeline($id)
    {   
        $string = '';
        if($id): $string .= ' AND u.id='.$id; else : $string .= ''; endif;

        $stringQuery = "SELECT h.id, h_idpipeline, h_cliente, h_qualificacao, h_proposta, h_negociacao, h_fechamento, h_tipooperacao,
        date_format(h_dt_proposta,'%Y-%m-%d')as h_dt_proposta, date_format(h_dtoperacao,'%Y-%m-%d')as h_dtoperacao, u.name as licenciado,
        date_format(h.datainicial,'%Y-%m-%d')as datainicial, date_format(h.datafinal,'%Y-%m-%d')as datafinal, h_idtotem
        
        from historicopipeline h, users u 
        where u.id = h_id_ult_alterador " . $string;

        return $stringQuery;
    }

    public function insereHistorico($dadosrequisicao, $idlicenciado, $nomecliente, $tipo)
    {
        if ($tipo == 1){
            $tipooperacao = 'Cadastro';
            $dataoperacao = $dadosrequisicao[0]->created_at;
            $campo_h_dt_proposta ='h_dt_proposta,';
            $criadoem = $dadosrequisicao[0]->created_at ."','";
            $idpipeline = $dadosrequisicao[0]->id;
            $nSerieTotem = $dadosrequisicao[0]->n_serie->n_serie;
        }

        if($tipo == 2){
            $tipooperacao = 'Atualização';
            $dataoperacao = date('Y-m-d');
            $campo_h_dt_proposta ='';
            $criadoem = "";
            $idpipeline = $dadosrequisicao[0]->id;
            $nSerieTotem = $dadosrequisicao[0]->nSerieTotem;

        }
        
        if($tipo == 3){

            $tipooperacao = 'Exclusão';
            $dataoperacao = date('Y-m-d');
            $campo_h_dt_proposta ='h_dt_proposta,';
            $criadoem = $dadosrequisicao->created_at ."','";

            
            $stringQuery = "INSERT INTO historicopipeline
            (h_idpipeline, h_cliente, h_qualificacao, h_proposta, h_negociacao, h_fechamento, h_tipooperacao,". $campo_h_dt_proposta ."h_dtoperacao, h_id_ult_alterador, datainicial, datafinal, h_idtotem)
            VALUES('".$dadosrequisicao->id."', '".$nomecliente."', '".$dadosrequisicao->qualificacao."', '".$dadosrequisicao->proposta."', '".$dadosrequisicao->fechamento."', '".$dadosrequisicao->negociacao."', '".$tipooperacao."', '". $criadoem .$dataoperacao."', '".$dadosrequisicao->idautor."', '". $dadosrequisicao->datainicial."', '". $dadosrequisicao->datafinal."','". $dadosrequisicao->h_idtotem ."')";

            return $stringQuery;
        }
        
            $stringQuery = "INSERT INTO historicopipeline
            (h_idpipeline, h_cliente, h_qualificacao, h_proposta, h_negociacao, h_fechamento, h_tipooperacao,". $campo_h_dt_proposta ."h_dtoperacao, h_id_ult_alterador, datainicial, datafinal, h_idtotem)
            VALUES('".$idpipeline."', '".$nomecliente."', '".$dadosrequisicao[0]->qualificacao."', '".$dadosrequisicao[0]->proposta."', '".$dadosrequisicao[0]->fechamento."', '".$dadosrequisicao[0]->negociacao."', '".$tipooperacao."', '". $criadoem .$dataoperacao."', '".$idlicenciado."', '". $dadosrequisicao[0]->datainicial."', '". $dadosrequisicao[0]->datafinal."','". $nSerieTotem ."')";


        // if($tipo == 4){
        //     $tipooperacao = 'Visualização de Pipeline';
        //     $dataoperacao = date('Y-m-d');
            
        //     $stringQuery = "INSERT INTO historicopipeline
        //     (id, h_idpipeline, h_cliente, h_qualificacao, h_proposta, h_negociacao, h_fechamento, h_tipooperacao, h_dt_proposta, h_dtoperacao, h_id_ult_alterador)
        //     VALUES(NULL, '".$pipelineatualizado->id."', '".$dadosrequisicao[0]->cliente."', '".$dadosrequisicao[0]->qualificacao."', '".$dadosrequisicao[0]->proposta."', '".$dadosrequisicao[0]->fechamento."', '".$dadosrequisicao[0]->negociacao."', '".$tipooperacao."', '".$dadosrequisicao[0]->created_at."', '".$dataoperacao."', '".$idlicenciado."')";
        // }
        // die($stringQuery);

        return $stringQuery;
    }
}
