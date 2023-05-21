<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Listagem;
use App\Models\Endereco;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\URL;

class HomeController extends Controller

{

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct(Listagem $listagem, Endereco $endereco )

    {
		$this->listagem = $listagem;
        $this->endereco = $endereco;

    }


    /**

     * Show the application dashboard.

     *

     * @return \Illuminate\Contracts\Support\Renderable

     */

    public function index(Request $request)
    {
        $recordsPerPage = $request->input('records', 20);
        $listagens = $this->listagem->getListagem($recordsPerPage);

        // Mantenha o valor do parâmetro 'records' na URL para preservar a seleção do usuário
        $listagens->appends(['records' => $recordsPerPage]);

		return view('ceps.index')->with([ 'listagens' => $listagens] );

    }


    public function enderecos(Request $request, $listId)
    {
        $recordsPerPage = $request->input('records', 20);
        $codigo_cliente = $request->codigo_cliente ? $request->codigo_cliente : null;
        $responses = $request->responses ? $request->responses : null;
        $uf = $request->uf ? $request->uf : null;
        $cidade = $request->cidade ? $request->cidade : null;

        $enderecos = $this->endereco->getEnderecos($recordsPerPage, $listId, $codigo_cliente, $responses, $uf, $cidade);

        $estados = $this->estados();

        // Mantenha o valor do parâmetro 'records' na URL para preservar a seleção do usuário
        $enderecos->appends(['records' => $recordsPerPage]);
        return view('ceps.enderecos')->with([ 'enderecos' => $enderecos, 'estados' => $estados, 'listId' => $listId ] );
    }

    public function estados(){
        $estados = [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins',
        ];

        return $estados;
    }

    public function exportar(Request $request)
    {
        $codigo_cliente = $request->codigo_cliente ? $request->codigo_cliente : null;
        $responses = $request->responses ? $request->responses : null;
        $uf = $request->uf ? $request->uf : null;
        $cidade = $request->cidade ? $request->cidade : null;
        $listId = $request->listId ? $request->listId : null;

        $enderecos = $this->endereco->getEnderecosTxt( $listId, $codigo_cliente, $responses, $uf, $cidade);

        $colunas = $this->endereco->first()->getFillable();

        $texto = implode(',', $colunas) . "\r\n"; // Cabeçalho das colunas

        foreach ($enderecos as $dado) {
            $linha = [];
            foreach ($colunas as $coluna) {
                $linha[] = $dado->{$coluna};
            }
            $texto .= implode(',', $linha) . "\r\n";
        }


        $fileName = 'exportacao_enderecos.txt';
        $filePath = public_path(str_replace('/', DIRECTORY_SEPARATOR, $fileName));

        File::put($filePath, $texto);

        return Response::download($filePath, $fileName)->deleteFileAfterSend();
    }

}

