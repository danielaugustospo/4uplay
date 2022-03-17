<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Totem extends Model
{

    protected $table = 'totem';

    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'id',
        'idcliente',
        'idlicenciado',
        'n_serie',
        'dtassociado',
        'id_usr_criador',
        'id_ult_alterador',
        'excluidototem'
    ];

    public function consultaTotem($permiteListagemCompleta, $id)
    {
        if ($permiteListagemCompleta == 0) {
            $complemento = " AND t.idlicenciado = " . $id;


            // $stringQuery = "SELECT t.*, u.name as 'licenciado', c.c_nome as 'cliente'
            //                 FROM totem t, users u, clienteslicenciado c
            //                 WHERE t.excluidototem = 0 

            //                     AND u.id = t.idlicenciado
            //                     -- AND c.id = t.idcliente  
            //                     " . $complemento;

            $stringQuery = "SELECT t.*, u.name as 'licenciado', c.c_nome as 'cliente'
                                FROM totem t
                                left join users u on t.idlicenciado = u.id 
                                left join clienteslicenciado c on t.idcliente = c.id
                                WHERE t.excluidototem = 0 ". $complemento;                    
        }
        if ($permiteListagemCompleta == 1) {

            // $stringQuery = "SELECT t.*, u.name as 'licenciado'
            //                 FROM totem t, users u
            //                 WHERE t.excluidototem = 0 

            //                     AND u.id = t.idlicenciado";

            $stringQuery = "SELECT t.*, u.name as 'licenciado'
                                from totem t
                                left join users u on t.idlicenciado = u.id
                                WHERE t.excluidototem = 0";
        }

        // if($permiteListagemCompleta == 1){
        //     $stringQuery = "SELECT * from users";
        // }
        return $stringQuery;
    }

    public function consultaHistoricoTotem($id)
    {
        $string = '';
        if ($id) : $string .= ' AND u.id=' . $id;
        else : $string .= '';
        endif;

        $stringQuery = "SELECT h.id, h_idpipeline, h_cliente, h_qualificacao, h_proposta, h_negociacao, h_fechamento, h_tipooperacao,
        date_format(h_dt_proposta,'%Y-%m-%d')as h_dt_proposta, date_format(h_dtoperacao,'%Y-%m-%d')as h_dtoperacao, u.name as licenciado
        
        from historicopipeline h, users u 
        where u.id = h_id_ult_alterador " . $string;

        return $stringQuery;
    }

    public function insereHistorico($dadosrequisicao, $idlicenciado, $nomecliente, $tipo)
    {
        if ($tipo == 1) {
            $tipooperacao = 'Cadastro';
            $dadosrequisicao[0]->updated_at = $dadosrequisicao[0]->created_at;

            $stringQuery = "INSERT INTO historico_totemcliente
            (id, h_idcliente, h_idtotem, h_totemassociado, h_dtassociado, h_excluidototem, created_at, updated_at)
            VALUES(NULL, '" . $idlicenciado  . "', '" . $dadosrequisicao[0]->n_serie . "', '" . $nomecliente . "', '" . $dadosrequisicao[0]->dtassociado . "',   '". $tipooperacao ."', '" . $dadosrequisicao[0]->created_at . "', '" . $dadosrequisicao[0]->updated_at . "')";
        }

        if ($tipo == 2) {
            $tipooperacao = 'Atualização';
            $dataoperacao = date('Y-m-d');

            $stringQuery = "INSERT INTO historico_totemcliente
            (id, h_idcliente, h_idtotem, h_totemassociado, h_dtassociado, h_excluidototem, updated_at)
            VALUES(NULL, '" . $idlicenciado  . "', '" . $dadosrequisicao[0]->n_serie . "', '" . $nomecliente . "', '" . $dadosrequisicao[0]->dtassociado . "',   '". $tipooperacao ."',  '" . $dataoperacao . "')";
        }

        if ($tipo == 3) {
            $tipooperacao = 'Exclusão';
            $dataoperacao = date('Y-m-d');

            $stringQuery = "INSERT INTO historico_totemcliente
            (id, h_idcliente, h_idtotem, h_totemassociado, h_dtassociado, h_excluidototem, updated_at)
            VALUES(NULL, '" . $idlicenciado  . "', '" . $dadosrequisicao->n_serie . "', '" . $nomecliente . "', '" . $dadosrequisicao->dtassociado . "',   '". $tipooperacao ."',  '" . $dataoperacao . "')";
        }
        if (($tipo == 4) || ($tipo == 5) ) {

            if ($tipo == 4) : $tipooperacao = 'Desassociado'; endif;
            if ($tipo == 5) : $tipooperacao = 'Associado'; endif;
            $dataoperacao = date('Y-m-d');

            $stringQuery = "INSERT INTO historico_totemcliente
            (id, h_idcliente, h_idtotem, h_totemassociado, h_dtassociado, h_excluidototem, updated_at)
            VALUES(NULL, '" . $idlicenciado  . "', '" . $dadosrequisicao[0]->n_serie . "', '" . $nomecliente . "', '" . $dadosrequisicao[0]->dtassociado . "',   '". $tipooperacao ."',  '" . $dataoperacao . "')";
        }

        return $stringQuery;
    }
}
