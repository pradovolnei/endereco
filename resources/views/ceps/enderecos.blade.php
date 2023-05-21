<style>
    .pagination a {
        display: inline-block;
        margin-right: 5px;
    }

    .pagination .active {
        font-weight: bold;
    }

</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('home') }}">Home</a>
                </div>
                LISTAGEM: {{ $listId }}
                <div class="card-body">
                    <form action="" method="GET">
                        <table border=1>
                            <tr>
                                <td> CODIGO IDENTIFICADOR </td>
                                <td> Status do CEP </td>
                                <td> UF </td>
                                <td> Cidade </td>
                            </tr>
                            <tr>
                                <td> <input type="text" name="codigo_cliente" id="codigo_cliente" value="{{ request('codigo_cliente') }}"> </td>
                                <td>
                                    <select name="responses" id="responses" >
                                        <option> </option>
                                        <option value="1" {{ request('responses') == "1" ? 'selected' : '' }} > Validado </option>
                                        <option value="ne" {{ request('responses') == "ne" ? 'selected' : '' }} > Não Encontrado </option>
                                    </select>
                                </td>
                                <td>
                                    <select name="uf" id="uf" >
                                        <option value="">Selecione um estado</option>
                                        @foreach ($estados as $sigla => $estado)
                                            <option value="{{ $sigla }}" {{ request('uf') == $sigla ? 'selected' : '' }} >{{ $estado }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td> <input type="text" name="cidade" id="cidade" value="{{ request('cidade') }}"> </td>
                            </tr>
                        </table>
                        <button type="submit">Filtrar</button>
                    </form>
                </div>

                <a href="{{ route('exportar', ['listId' => $listId, 'uf' => request('uf'), 'cidade' => request('cidade'), 'responses' => request('responses'), 'codigo_cliente' => request('codigo_cliente') ]) }}">Exportar para TXT</a>


                <h1>Endereços</h1>
                <!-- Select para escolher o número de registros por página -->
                <span>Registros por página:</span>
                <select id="recordsPerPage" onchange="changeRecordsPerPage(this.value)">
                    <option value="20" {{ $enderecos->perPage() == "20" ? 'selected' : '' }}>20</option>
                    <option value="50" {{ $enderecos->perPage() == "50" ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $enderecos->perPage() == "100" ? 'selected' : '' }}>100</option>
                    <option value="200" {{ $enderecos->perPage() == "200" ? 'selected' : '' }}>200</option>
                </select>
                <table border=1>
                    <thead>
                        <th> Código do cliente </th>
                        <th> CODIGO IDENTIFICADOR </th>
                        <th> CEP </th>
                        <th> Endereço </th>
                        <th> Status </th>
                    </thead>
                    <tbody>
                        @foreach ($enderecos as $endereco)
                            <tr>
                                <td> {{ $endereco->codigo_cliente }}</a></td>
                                <td> {{ $endereco->codigo_id }} </td>
                                <td> {{ $endereco->cep }} </td>
                                <td> {{ $endereco->cidade }} - {{ $endereco->uf }} </td>
                                <td> {{ $endereco->responses == 1 ? 'Validado' : 'Não Encontrado' }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Renderização da paginação -->
                @if ($enderecos->lastPage() > 1)
                    <div class="pagination">
                        @if ($enderecos->currentPage() > 1)
                            <a href="{{ $enderecos->previousPageUrl() }}">Anterior</a>
                        @endif

                        @for ($i = 1; $i <= $enderecos->lastPage(); $i++)
                            <a href="{{ $enderecos->url($i) }}" class="{{ ($enderecos->currentPage() == $i) ? 'active' : '' }}">{{ $i }}</a>
                        @endfor

                        @if ($enderecos->currentPage() < $enderecos->lastPage())
                            <a href="{{ $enderecos->nextPageUrl() }}">Próxima</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function changeRecordsPerPage() {
        var selectElement = document.getElementById('recordsPerPage');
        var recordsPerPage = selectElement.value;

        var currentUrl = "{{ $enderecos->url($enderecos->currentPage()) }}";
        var updatedUrl = currentUrl.split('?')[0] + '?records=' + recordsPerPage;

        window.location.href = updatedUrl;
    }
</script>

