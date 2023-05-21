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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="carga.php" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <div class="col-md-8">
                                <label> Carregar listagem (xls/xlsx) </label> <br>
                                <input id="file" type="file" class="form-control" name="file" accept=".xls, .xlsx" >
                                <button type="submit" class="btn btn-primary">
                                    Carregar
                                </button>

                            </div>
                        </div>

                    </form>
                </div>

                <h1>Listagem</h1>
                <!-- Select para escolher o número de registros por página -->
                <span>Registros por página:</span>
                <select id="recordsPerPage" onchange="changeRecordsPerPage(this.value)">
                    <option value="20" {{ $listagens->perPage() == "20" ? 'selected' : '' }}>20</option>
                    <option value="50" {{ $listagens->perPage() == "50" ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $listagens->perPage() == "100" ? 'selected' : '' }}>100</option>
                    <option value="200" {{ $listagens->perPage() == "200" ? 'selected' : '' }}>200</option>
                </select>
                <table border=1>
                    <thead>
                        <th> Listagem </th>
                        <th> Lidos </th>
                        <th> Validados </th>
                        <th> Data de Validação </th>
                    </thead>
                    <tbody>
                        @foreach ($listagens as $listagem)
                            <tr>
                                <td><a href="{{ route('enderecos', ['listId' => $listagem->id]) }}">{{ $listagem->id }}</a></td>
                                <td> {{ $listagem->lidos }} </td>
                                <td> {{ $listagem->validados }} </td>
                                <td> {{ $listagem->created_at }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Renderização da paginação -->
                @if ($listagens->lastPage() > 1)
                    <div class="pagination">
                        @if ($listagens->currentPage() > 1)
                            <a href="{{ $listagens->previousPageUrl() }}">Anterior</a>
                        @endif

                        @for ($i = 1; $i <= $listagens->lastPage(); $i++)
                            <a href="{{ $listagens->url($i) }}" class="{{ ($listagens->currentPage() == $i) ? 'active' : '' }}">{{ $i }}</a>
                        @endfor

                        @if ($listagens->currentPage() < $listagens->lastPage())
                            <a href="{{ $listagens->nextPageUrl() }}">Próxima</a>
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

        var currentUrl = "{{ $listagens->url($listagens->currentPage()) }}";
        var updatedUrl = currentUrl.split('?')[0] + '?records=' + recordsPerPage;

        window.location.href = updatedUrl;
    }
</script>

