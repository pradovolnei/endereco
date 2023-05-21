<?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "endereco";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifique a conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    require_once 'PHPExcel/Classes/PHPExcel.php';

    //$nome_arquivo = 'cadastro.xls';

    $nome_arquivo = $_FILES['file']['tmp_name'];

    $excelReader = PHPExcel_IOFactory::createReaderForFile($nome_arquivo);
    $excelReader->setReadDataOnly(true);
    $objPHPExcel = $excelReader->load($nome_arquivo);
    $worksheet = $objPHPExcel->getActiveSheet();

    $registros = array();
    $primeiraLinha = true;

    foreach ($worksheet->getRowIterator() as $row) {
        if ($primeiraLinha) {
            $primeiraLinha = false;
            continue; // Pula a primeira linha e passa para a próxima iteração
        }
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $dados = array();

        foreach ($cellIterator as $cell) {
            $dados[] = $cell->getValue();
        }

        // Aqui você pode ajustar os índices dos dados de acordo com as colunas do seu arquivo XLS
        $codigo_cliente = $dados[0];
        $codigo_id = $dados[1];
        $cep = preg_replace("/[^0-9]/", "", $dados[2]);

        if($cep){
            // Obtém o conteúdo do endpoint
            $json = file_get_contents("https://viacep.com.br/ws/$cep/json/");

            // Decodifica o JSON em um objeto
            $objeto = json_decode($json);

            $uf = "";
            $cidade = "";
            $status = 0;

            if(isset($objeto->cep)){
                $status = 1;
                $cidade = $objeto->localidade;
                $uf = $objeto->uf;
            }
        }else{
            $uf = "";
            $cidade = "";
            $status = 0;
        }

        // Salve os dados em um array para posterior inserção no banco de dados
        $registros[] = array(
            'codigo_cliente' => $codigo_cliente,
            'codigo_id' => $codigo_id,
            'cep' => $cep,
            'status' => $status,
            'uf' => $uf,
            'cidade' => $cidade
        );
    }

    // Prepare a declaração SQL
    $stmt_list = $conn->prepare("INSERT INTO listagem VALUES (NULL, NOW())");
    $stmt_list->execute();

    $id_listagem = $conn->insert_id;

    $stmt = $conn->prepare("INSERT INTO endereco (codigo_cliente, codigo_id, cep, uf, cidade, responses, id_listagem) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Itere sobre os registros e execute a inserção
    foreach ($registros as $registro) {
        $codigo_cliente = $registro['codigo_cliente'];
        $codigo_id = $registro['codigo_id'];
        $cep = $registro['cep'];
        $uf = $registro['uf'];
        $cidade = $registro['cidade'];

        // Obtém o conteúdo do endpoint
        $json = file_get_contents("https://viacep.com.br/ws/$cep/json/");

        // Decodifica o JSON em um objeto
        $objeto = json_decode($json);

        if (isset($objeto->cep)) {
            $status = 1;
        } else {
            $status = 0;
        }

        // Vincule os parâmetros
        $stmt->bind_param("sssssii", $codigo_cliente, $codigo_id, $cep, $uf, $cidade, $status, $id_listagem);

        // Execute a inserção
        $stmt->execute();
    }

    // Feche a declaração
    $stmt->close();

    // Feche a conexão com o banco de dados
    $conn->close();

    $mensagem = "Planilha carregada com sucesso!";

    header("Location: enderecos/".$id_listagem );

?>
