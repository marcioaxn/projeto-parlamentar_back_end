<style>
    .user-selection-container {
        background-color: #fff;
    }

    .user-item .card {
        transition: all 0.3s ease;
    }

    .user-item .card:hover {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container-fluid px-0">
    <div class="row">
        <div class="col-12">
            <div class="bg-white p-4">
                <div class="mb-4">
                    <h4>1. Informações Básicas</h4>
                    <hr>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('cod_parlamentar', 'Parlamentar', ['class' => 'form-label']) !!}
                                {!! Form::select('cod_parlamentar', $parlamentares->pluck('nom_parlamentar', 'cod_parlamentar'), null, [
                                    'class' => 'form-select select2',
                                    'placeholder' => 'Selecione um parlamentar',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('nom_gabinete', 'Nome do Gabinete', ['class' => 'form-label']) !!}
                                {!! Form::text('nom_gabinete', null, ['class' => 'form-control', 'placeholder' => 'Digite o nome do gabinete']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h4>2. Seleção de Usuários</h4>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchUsers"
                                            placeholder="Buscar usuários...">
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="toggleSelection">Selecionar Todos</button>
                                    </div>
                                </div>
                                <div class="user-selection-container border rounded p-3"
                                    style="max-height: 400px; overflow-y: auto;">
                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" id="usersGrid">
                                        @foreach ($users as $user)
                                            <div class="col user-item">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="form-check me-3">
                                                                {!! Form::checkbox(
                                                                    'users[]',
                                                                    $user->cod_user,
                                                                    isset($selectedUsers) ? in_array($user->cod_user, $selectedUsers) : false,
                                                                    ['class' => 'form-check-input user-select'],
                                                                ) !!}
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1">{{ $user->name }}</h6>
                                                                <div class="form-check">
                                                                    {!! Form::checkbox(
                                                                        'acesso_total[' . $user->cod_user . ']',
                                                                        true,
                                                                        isset($userAccessTotal) ? in_array($user->cod_user, $userAccessTotal) : false,
                                                                        ['class' => 'form-check-input'],
                                                                    ) !!}
                                                                    <label class="form-check-label small">Acesso
                                                                        Total</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h4>3. Status do Gabinete</h4>
                    <hr>
                    <div class="form-check">
                        {!! Form::checkbox('sta_ativo', 1, null, ['class' => 'form-check-input']) !!}
                        {!! Form::label('sta_ativo', 'Ativo', ['class' => 'form-check-label']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    // Script original do Select2 que funcionava
    $(document).ready(function() {
        $('#cod_parlamentar').select2();
        $(document).on("select2:open", () => {
            document.querySelector('.select2-container--open .select2-search__field').focus();
        });

        // Preenchimento automático do nome do gabinete
        $('#cod_parlamentar').on('select2:select', function(e) {
            var selectedText = e.params.data.text;
            $('#nom_gabinete').val(selectedText);
        });
    });

    // Script separado para a busca de usuários
    $(document).ready(function() {
        // Função de busca de usuários
        function searchUsers() {
            var searchValue = $('#searchUsers').val().toLowerCase().trim();

            $('.user-item').each(function() {
                var userName = $(this).find('h6').text().toLowerCase();
                $(this)[userName.indexOf(searchValue) !== -1 ? 'show' : 'hide']();
            });
        }

        // Evento de busca
        $('#searchUsers').on('keyup', searchUsers);
        $('#searchUsers').on('search', searchUsers); // Para quando clica no X do campo de busca

        // Selecionar/Deselecionar todos
        var allSelected = false;
        $('#toggleSelection').click(function() {
            allSelected = !allSelected;
            $('.user-select').prop('checked', allSelected);
            $(this).text(allSelected ? 'Desselecionar Todos' : 'Selecionar Todos');
        });
    });
</script>
