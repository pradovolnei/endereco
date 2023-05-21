<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Base;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Endereco extends Base
{
    protected $table = "endereco";

    protected $fillable = [
        'codigo_cliente',
        'codigo_id',
        'cep',
        'uf',
        'cidade',
        'responses',
        'id_listagem',
    ];

    public function getEnderecos($recordsPerPage = 100, $listId, $codigo_cliente, $responses, $uf, $cidade)
    {
        $dados = $this->where("id_listagem", $listId);

        if($codigo_cliente){
            $dados->where("codigo_cliente", $codigo_cliente);
        }

        if($responses){
            if($responses == 'ne'){
                $dados->where("responses", '0');
            }else{
                $dados->where("responses", $responses);
            }
        }

        if($uf){
            $dados->where("uf", $uf);
        }

        if($cidade){
            $dados->where("cidade", $cidade);
        }


        return $dados->paginate($recordsPerPage);;
    }

    public function getEnderecosTxt($listId, $codigo_cliente, $responses, $uf, $cidade)
    {
        $dados = $this->where("id_listagem", $listId);

        if($codigo_cliente){
            $dados->where("codigo_cliente", $codigo_cliente);
        }

        if($responses){
            if($responses == 'ne'){
                $dados->where("responses", '0');
            }else{
                $dados->where("responses", $responses);
            }
        }

        if($uf){
            $dados->where("uf", $uf);
        }

        if($cidade){
            $dados->where("cidade", $cidade);
        }


        return $dados->get();;
    }

}
