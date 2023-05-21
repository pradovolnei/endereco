@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"></a></li>
              <!--<li class="breadcrumb-item active">Dashboard v1</li>-->
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col-md-12-offset-2 " >
					<img src="img/logo-azul-pensajur.png" />
					
				</div>
			</div>
			<form action="{{ url( 'busca' ) }}" method="get">
			<!--<div class="row justify-content-center">
				

				<div class="col-md-8-offset-2 ">
					<h3>PESQUISA INTELIGENTE DA LEGISLAÇÃO BRASILEIRA</h3>
					  <div class="input-group" >
						<input class="form-control" type="search" placeholder="Pesquisar" aria-label="Search" style="border-right: none;border-radius: 29px 0 0 29px;height:50px;" name="b" >
						
						<div class="input-group-append" >
							
						  <div class="input-group-text" type="button" style="background-color: #FFF;border-radius: 0 29px 29px 0;"><button type="submit" aria-label="Gerar relatório pdf"><i class="fas fa-search"></i></button></div>
						  
						</div>
						
					  </div>
					  
					</div>
			</div>
			<br><br><br><br><br>-->
			
			<div class="row">
				
				
				<div class="col-md-2 ">
					<select class="form-control  select2" name="tipos" id="tipos" onChange="tipoLei(this.value);" >
						<option  > Tipos de Leis </option>
						<option value=1 > Códigos </option>
						<option value=2 > Leis Delegadas </option>
						<option value=3 > Leis Ordinárias </option>
						<option value=4 > Leis Complementares </option>
					</select>
				</div>
				
				<div class="col-md-2 ">
					<select class="form-control  select2" name="codigos" id="codigos" onChange="listCodes(this.value);" >
						<option > Leis </option>
						@foreach($lista_codigos as $codes)
							<option value="{{ $codes->id }}" > {{ $codes->nome }} </option>
						@endforeach
						@foreach($lista_partes as $codes)
							<option value="{{ $codes->id }}" > {{ $codes->parte }} </option>
						@endforeach
						@foreach($lista_titulos as $codes)
							<option value="{{ $codes->id }}" > {{ $codes->titulo }} </option>
						@endforeach
					</select>

				</div>
				
				<div class="col-md-3 ">
				<input type="text" class="form-control" name="b" placeholder="Pesquisar" />
				</div>
				<div class="col-md-3 ">
				<input type="submit"  value="Buscar" />
				</div>
			</div>
			</form>
			<?php require_once('simplehtmldom/simple_html_dom.php'); ?>
			<?php 
			
				if(isset($_GET["busca"])){
					//echo $_GET["busca"];
					$query = 'tipoDocumento+="Lei"';
					$query .= '+and+date+="'.$_GET["busca"].'"';
					$query .= '+or+urn+="'.$_GET["busca"].'"';
					$query .= '+or+title+="'.$_GET["busca"].'"';
					$query .= '+or+description+="'.$_GET["busca"].'"';
					//$url = 'https://www.lexml.gov.br/busca/SRU?operation=searchRetrieve&query=description+="'.$_GET["busca"].'"+and+tipoDocumento+="Lei"';
					$url = 'https://www.lexml.gov.br/busca/SRU?operation=searchRetrieve&query='.$query;
					//$url = 'https://www.lexml.gov.br/busca/SRU?operation=searchRetrieve&query=description+="aborto"';
					echo $url;
					$html = file_get_html($url);
					//echo count($html->find("srw:record"));
					if(count($html->find("srw:record"))>0){
				   foreach($html->find("srw:record") as $element){
					   echo 'Tipo de documento: '.$element->find('tipoDocumento',0) . '<br/>';
					  // echo 'facet-tipoDocumento: '. $element->find('facet-tipoDocumento',0)->plaintext . '<br/>';
					   echo 'dc:date: '. $element->find('dc:date',0) . '<br/>';
					   echo 'URN: '. $element->find('urn',0)->plaintext . '<br/>';
					   if(isset($element->find('localidade',0)->plaintext)){
					   echo 'localidade: '. $element->find('localidade',0)->plaintext . '<br/>';}
					    if(isset($element->find('facet-localidade',0)->plaintext)){
						echo 'localidade: '. $element->find('facet-localidade',0)->plaintext . '<br/>';}
						 if(isset($element->find('autoridade',0)->plaintext)){
						 echo 'autoridade: '. $element->find('autoridade',0)->plaintext . '<br/>';}
						 if(isset($element->find('facet-autoridade',0)->plaintext)){
						 echo 'autoridade: '. $element->find('facet-autoridade',0)->plaintext . '<br/>';}
					   echo 'titulo: '. $element->find('dc:title',0)->plaintext . '<br/>';
					 //  echo 'Descrição: '. $element->find('dc:description',0)->plaintext . '<br/>';
					   //echo 'dc:type: '. $element->find('dc:type',0)->plaintext . '<br/>';
					   //echo 'dc:identifier: '. $element->find('dc:identifier',0)->plaintext . '<br/>';

						echo '====<br/><br/>';
					}
					}
				}
			?>
		</div>
	</section>
    <!-- /.content -->
  </div>

 @endsection