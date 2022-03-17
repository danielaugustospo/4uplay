<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Criativo extends Model
{


    protected $table = 'criativo';

    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'cliente',
        'tipocriativo',
        'quantidade',
        'valunit',
        'valtotal',
        'idlicenciado',
        'id_ult_alterador',
        'excluidocriativo',
        'idtotem',
        'datacriacao',

    ];

    public function consultaCriativo($id)
    {   
        $string = '';
        if($id){ $string .= ' AND u.id= '.$id; }else { $string .= ''; }

        $stringQuery = "SELECT cri.*, u.name  as 'licenciado', t.n_serie as idtotem, cli.c_nome as 'cliente' 
                        FROM criativo cri

                        
                        left join users u on cri.idlicenciado = u.id 
                        left join clienteslicenciado cli on cri.cliente = `cli`.`id`
                        left join totem t on cri.idtotem = `t`.`id`


                        WHERE cri.excluidocriativo = 0 " . $string . " GROUP BY id";

        return $stringQuery;
    }

    public function consultaHistoricoCriativo($id)
    {   
        $string = '';
        if($id): $string .= ' AND u.id='.$id; else : $string .= ''; endif;

        $stringQuery = "SELECT h.id, h_idpipeline, h_cliente, h_qualificacao, h_proposta, h_negociacao, h_fechamento, h_tipooperacao,
        date_format(h_dt_proposta,'%Y-%m-%d')as h_dt_proposta, date_format(h_dtoperacao,'%Y-%m-%d')as h_dtoperacao, hc_idtotem, hc_datacriacao, u.name as licenciado
        
        from historicopipeline h, users u 
        where u.id = h_id_ult_alterador " . $string;

        return $stringQuery;
    }

    
    public function insereHistorico($dadosrequisicao, $idlicenciado, $nomeCliente, $tipo)
    {
        if ($tipo == 1){
            $tipooperacao = 'Cadastro';
            $dadosrequisicao[0]->created_at = date('Y-m-d');
            $dadosrequisicao[0]->updated_at = $dadosrequisicao[0]->created_at;
            $campo_h_dt_proposta ='h_dt_proposta,';

            $stringQuery = "INSERT INTO historicocriativo
            (hc_id, hc_idcriativo, hc_cliente, hc_tipocriativo, hc_quantidade, hc_valunit, hc_valtotal, hc_tipooperacao, hc_idlicenciado, hc_id_ult_alterador,  hc_dataalteracao, hc_idtotem, hc_datacriacao)
            VALUES(NULL, '".$dadosrequisicao[0]->id."', '".$nomeCliente."', '".$dadosrequisicao[0]->tipocriativo."', '".$dadosrequisicao[0]->quantidade."', '".$dadosrequisicao[0]->valunit."', '".$dadosrequisicao[0]->valtotal."', '".$tipooperacao."', '".$idlicenciado."', '".$idlicenciado."',  '".$dadosrequisicao[0]->datacriacao."' , '".$dadosrequisicao[0]->nSerieTotem."',  '".$dadosrequisicao[0]->datacriacao."')";

        }

        if($tipo == 2){


            $tipooperacao = 'Atualização';
            $dataoperacao = date('Y-m-d');
            $campo_h_dt_proposta ='';
            $criadoem = "";
            $idpipeline = $dadosrequisicao[0]->id;

            $stringQuery = "INSERT INTO historicocriativo
            (hc_id, hc_idcriativo, hc_cliente, hc_tipocriativo, hc_quantidade, hc_valunit, hc_valtotal, hc_tipooperacao, hc_idlicenciado, hc_id_ult_alterador, hc_dataalteracao, hc_idtotem, hc_datacriacao)
            VALUES(NULL, '".$dadosrequisicao[0]->id."', '".$nomeCliente."', '".$dadosrequisicao[0]->tipocriativo."', '".$dadosrequisicao[0]->quantidade."', '".$dadosrequisicao[0]->valunit."', '".$dadosrequisicao[0]->valtotal."', '".$tipooperacao."', '".$idlicenciado."', '".$idlicenciado."', '".$dataoperacao."',  '".$dadosrequisicao[0]->nSerieTotem."',  '".$dadosrequisicao[0]->datacriacao."')";

        }
        
        if($tipo == 3){

            $tipooperacao = 'Exclusão';
            $dataoperacao = date('Y-m-d');
            $campo_h_dt_proposta ='h_dt_proposta,';
            $criadoem = $dadosrequisicao->created_at ."','";

            $stringQuery = "INSERT INTO historicocriativo
            (hc_id, hc_idcriativo, hc_cliente, hc_tipocriativo, hc_quantidade, hc_valunit, hc_valtotal, hc_tipooperacao, hc_idlicenciado, hc_id_ult_alterador, hc_dataalteracao, hc_idtotem, hc_datacriacao)
            VALUES(NULL, '".$dadosrequisicao->id."', '".$nomeCliente."', '".$dadosrequisicao->tipocriativo."', '".$dadosrequisicao->quantidade."', '".$dadosrequisicao->valunit."', '".$dadosrequisicao->valtotal."', '".$tipooperacao."', '".$dadosrequisicao->idlicenciado."', '".$dadosrequisicao->id_ult_alterador."', '".$dataoperacao."',  '".$dadosrequisicao->nSerieTotem."',  '".$dadosrequisicao->datacriacao."')";

        }
        return $stringQuery;


        // if($tipo == 4){
        //     $tipooperacao = 'Visualização de Pipeline';
        //     $dataoperacao = date('Y-m-d');
            
        //     $stringQuery = "INSERT INTO historicopipeline
        //     (id, h_idpipeline, h_cliente, h_qualificacao, h_proposta, h_negociacao, h_fechamento, h_tipooperacao, h_dt_proposta, h_dtoperacao, h_id_ult_alterador)
        //     VALUES(NULL, '".$pipelineatualizado->id."', '".$dadosrequisicao[0]->cliente."', '".$dadosrequisicao[0]->qualificacao."', '".$dadosrequisicao[0]->proposta."', '".$dadosrequisicao[0]->fechamento."', '".$dadosrequisicao[0]->negociacao."', '".$tipooperacao."', '".$dadosrequisicao[0]->created_at."', '".$dataoperacao."', '".$idlicenciado."')";
        // }
        // die($stringQuery);

    }
}
