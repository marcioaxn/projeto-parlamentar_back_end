@extends('layouts.app')

@section('content')
    <!-- Início breadcrumbs -->
    <div id="portal-breadcrumbs-wrapper" class="m-0 pl-0 mb-3 d-print-none">
        <nav id="breadcrumbs" aria-label="Histórico de navegação (Breadcrumbs)">
            <div class="content">
                <span class="sr-only">Você está aqui:</span>
                <span class="home">
                    <a href="{!! url('/') !!}">
                        <span class="fas fa-home" aria-hidden="true"></span>
                        <span class="sr-only">Página Inicial</span>
                    </a>
                </span>

                <span class="pl-1 pr-1">></span>

                <span dir="ltr" id="breadcrumbs-2">
                    <span id="breadcrumbs-current">Relatórios</span>
                </span>

            </div>
        </nav>
    </div>
    <!-- Fim breadcrumbs -->

    <div class="row">

        {!! Form::open([
            'class' => 'p-0',
            'method' => 'post',
            'url' => route('relatorios.pagina'),
            'target' => '_blank',
        ]) !!}

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-4 pt-3">

            <div class="card shadow-sm">
                <div class="card-header cardTemas p-1 pt-2 pl-3 pb-2">
                    <i class="fas fa-user-circle"></i> Carômetro Senadores(as) e Deputados(as) Federais
                </div>
                <div class="card-body bg-white pt-2 pb-2">

                    <div class="row m-0 p-0">

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mt-1 mb-1">

                            <div class="row">
                                <div class="col-12 mb-2">

                                    <label for="partido" class="form-label">Partido</label>
                                    {!! Form::select('partido', $partidosSelect, 'Todos', [
                                        'class' => 'form-control text-dark',
                                        'multiple' => true,
                                        'style' => 'cursor: pointer; width: 100% !important;',
                                        'id' => 'partido',
                                        'autocomplete' => 'off',
                                    ]) !!}

                                    <script type="text/javascript">
                                        $('#partido').select2();

                                        var selectPartido = $('#partido');

                                        $('#partido').on('change', function() {
                                            var selectedValues = $('#partido').val();

                                            // Verifica se o item "Todos" está selecionado e se outros valores também estão selecionados
                                            if (selectedValues && selectedValues.includes("Todos") && selectedValues.length > 1) {
                                                // Remove o valor "Todos" da seleção
                                                selectedValues = selectedValues.filter(value => value !== "Todos");
                                                $('#partido').val(selectedValues).trigger('change');
                                            }

                                        });

                                        function desselecionarTodos() {
                                            $('#partido option').prop('selected', false);

                                            selectPartido.find('option[value="Todos"]').prop('selected', true);
                                            $('#partido').trigger('change');
                                        }

                                        selectPartido.on('select2:select', function(e) {
                                            if (e.params.data.id === 'Todos') {
                                                $('#partido option').prop('selected', false);

                                                selectPartido.find('option[value="Todos"]').prop('selected', true);
                                                $('#partido').trigger('change');
                                            }
                                        });
                                    </script>

                                </div>
                                <div class="col-12 text-right mb-2">

                                    <button type="button" class="btn btn-outline-secondary" onclick="desselecionarTodos();"
                                        style="font-size: 0.8rem!Important;">Limpar seleção e mostrar todos</button>

                                </div>
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mt-1 mb-1">

                            <div class="row">
                                <div class="col-12 mb-2">

                                    <label for="dsc_casa" class="form-label">Casa</label>
                                    {!! Form::select('dsc_casa', $casaSelect, 'Todas', [
                                        'class' => 'form-control text-dark',
                                        'multiple' => true,
                                        'style' => 'cursor: pointer; width: 100% !important;',
                                        'id' => 'dsc_casa',
                                        'autocomplete' => 'off',
                                    ]) !!}

                                    <script type="text/javascript">
                                        $('#dsc_casa').select2();

                                        var selectCasa = $('#dsc_casa');

                                        $('#dsc_casa').on('change', function() {
                                            var selectedValues = $('#dsc_casa').val();

                                            // Verifica se o item "Todas" está selecionado e se outros valores também estão selecionados
                                            if (selectedValues && selectedValues.includes("Todas") && selectedValues.length > 1) {
                                                // Remove o valor "Todas" da seleção
                                                selectedValues = selectedValues.filter(value => value !== "Todas");
                                                $('#dsc_casa').val(selectedValues).trigger('change');
                                            }

                                        });

                                        function desselecionarTodosCasa() {
                                            $('#dsc_casa option').prop('selected', false);

                                            selectCasa.find('option[value="Todas"]').prop('selected', true);
                                            $('#dsc_casa').trigger('change');
                                        }

                                        selectCasa.on('select2:select', function(e) {
                                            if (e.params.data.id === 'Todas') {
                                                $('#dsc_casa option').prop('selected', false);

                                                selectCasa.find('option[value="Todas"]').prop('selected', true);
                                                $('#dsc_casa').trigger('change');
                                            }
                                        });
                                    </script>

                                </div>
                                <div class="col-12 text-right mb-2">

                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="desselecionarTodosCasa();" style="font-size: 0.8rem!Important;">Limpar
                                        seleção e mostrar todas</button>

                                </div>
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mt-1 mb-1">

                            <div class="row">
                                <div class="col-12 mb-2">

                                    <label for="sgl_uf_representante" class="form-label">UF de representação</label>
                                    {!! Form::select('sgl_uf_representante[]', $ufRepresentacaoSelect, 'Todas', [
                                        'class' => 'form-control text-dark',
                                        'multiple' => true,
                                        'style' => 'cursor: pointer; width: 100% !important;',
                                        'id' => 'sgl_uf_representante',
                                        'autocomplete' => 'off',
                                    ]) !!}

                                    <script type="text/javascript">
                                        $('#sgl_uf_representante').select2();

                                        var select = $('#sgl_uf_representante');

                                        $('#sgl_uf_representante').on('change', function() {
                                            var selectedValues = $('#sgl_uf_representante').val();

                                            // Verifica se o item "Todas" está selecionado e se outros valores também estão selecionados
                                            if (selectedValues && selectedValues.includes("Todas") && selectedValues.length > 1) {
                                                // Remove o valor "Todas" da seleção
                                                selectedValues = selectedValues.filter(value => value !== "Todas");
                                                $('#sgl_uf_representante').val(selectedValues).trigger('change');
                                            }

                                        });

                                        function desselecionarTodosUFRepresentacao() {
                                            $('#sgl_uf_representante option').prop('selected', false);

                                            select.find('option[value="Todas"]').prop('selected', true);
                                            $('#sgl_uf_representante').trigger('change');
                                        }

                                        select.on('select2:select', function(e) {
                                            if (e.params.data.id === 'Todas') {
                                                $('#sgl_uf_representante option').prop('selected', false);

                                                select.find('option[value="Todas"]').prop('selected', true);
                                                $('#sgl_uf_representante').trigger('change');
                                            }
                                        });
                                    </script>

                                </div>
                                <div class="col-12 text-right mb-2">

                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="desselecionarTodosUFRepresentacao();"
                                        style="font-size: 0.8rem!Important;">Limpar seleção e mostrar todas</button>

                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-footer bg-white text-right">

                    <div class="row">

                        <div class="col-12 ">

                            <button type="button" class="btn btn-outline-danger mt-1 mb-1"
                                onclick="consultar('partido-pdf', $('#partido').val(), $('#dsc_casa').val(), $('#sgl_uf_representante').val());"
                                style="font-size: 0.8rem!Important;"><i class="fas fa-file-pdf"
                                    style="font-size: 1rem!Important;"></i>Gerar <strong>PDF</strong> agrupado por <span
                                    class="text-bold">Partido(s)</span></button>

                            <button type="button" class="btn btn-outline-danger mt-1 mb-1"
                                onclick="consultar('uf-pdf', $('#partido').val(), $('#dsc_casa').val(), $('#sgl_uf_representante').val());"
                                style="font-size: 0.8rem!Important;"><i class="fas fa-file-pdf"
                                    style="font-size: 1rem!Important;"></i>Gerar <strong>PDF</strong> agrupado por <span
                                    class="text-bold">Estado(s)</span></button>

                            <button type="button" class="btn btn-outline-primary mt-1 mb-1"
                                onclick="consultar('partido-word', $('#partido').val(), $('#dsc_casa').val(), $('#sgl_uf_representante').val());"
                                style="font-size: 0.8rem!Important;"><i class="fas fa-file-word"
                                    style="font-size: 1rem!Important;"></i>Gerar <strong>Word</strong> agrupado por <span
                                    class="text-bold">Partido(s)</span></button>

                            <button type="button" class="btn btn-outline-primary mt-1 mb-1"
                                onclick="consultar('uf-word', $('#partido').val(), $('#dsc_casa').val(), $('#sgl_uf_representante').val());"
                                style="font-size: 0.8rem!Important;"><i class="fas fa-file-word"
                                    style="font-size: 1rem!Important;"></i>Gerar <strong>Word</strong> agrupado por <span
                                    class="text-bold">Estado(s)</span></button>

                            {{-- <button type="submit" class="btn btn-outline-secondary mt-1 mb-1"
                                style="font-size: 0.8rem!Important;"><i class="fas fa-file-alt"
                                    style="font-size: 1rem!Important;"></i>Gerar página agrupado por <span
                                    class="text-bold">Estado(s)</span></button> --}}

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-4 pt-3">

            <div class="card shadow-sm">
                <div class="card-header cardTemas p-1 pt-2 pl-3 pb-2">
                    <i class="fas fa-user-circle"></i> Base de dados acerca dos Parlamentares Federais
                </div>
                <div class="card-footer bg-white text-right">

                    <div class="row m-0 p-0">

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt-1 mb-1">

                            <a class="btn btn-outline-success mt-1 mb-1"
                                href="{{ route('relatorios.get.export-dados-parlamentares') }}"
                                style="font-size: 0.8rem!Important;"><i class="fas fa-file-excel"
                                    style="font-size: 1rem!Important;"></i>Gerar <strong>Excel</strong></a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-4 pt-3">

            <div class="card shadow-sm">
                <div class="card-header cardTemas p-1 pt-2 pl-3 pb-2">
                    <i class="fas fa-hand-holding-usd"></i> Fundos Constitucionais de Financiamento
                </div>
                <div class="card-body bg-white pt-2 pb-2">

                    <div class="row m-0 p-0">

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mt-1 mb-1">

                            <div class="row">
                                <div class="col-12 mb-2">

                                    <label for="fundo" class="form-label">Fundo Regional</label>
                                    {!! Form::select('fundo', ['Todos' => 'Todos', 'FCO' => 'FCO', 'FNE' => 'FNE', 'FNO' => 'FNO'], 'Todos', [
                                        'class' => 'form-control text-dark',
                                        'multiple' => true,
                                        'style' => 'cursor: pointer; width: 100% !important;',
                                        'id' => 'fundo',
                                        'autocomplete' => 'off',
                                    ]) !!}

                                    <script type="text/javascript">
                                        $('#fundo').select2();

                                        var selectPartido = $('#fundo');

                                        $('#fundo').on('change', function() {
                                            var selectedValues = $('#fundo').val();

                                            // Verifica se o item "Todos" está selecionado e se outros valores também estão selecionados
                                            if (selectedValues && selectedValues.includes("Todos") && selectedValues.length > 1) {
                                                // Remove o valor "Todos" da seleção
                                                selectedValues = selectedValues.filter(value => value !== "Todos");
                                                $('#fundo').val(selectedValues).trigger('change');
                                            }

                                        });

                                        function desselecionarTodosFundos() {
                                            $('#fundo option').prop('selected', false);

                                            selectPartido.find('option[value="Todos"]').prop('selected', true);
                                            $('#fundo').trigger('change');
                                        }

                                        selectPartido.on('select2:select', function(e) {
                                            if (e.params.data.id === 'Todos') {
                                                $('#fundo option').prop('selected', false);

                                                selectPartido.find('option[value="Todos"]').prop('selected', true);
                                                $('#fundo').trigger('change');
                                            }
                                        });
                                    </script>

                                </div>

                                <div class="col-12 text-right mb-2">

                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="desselecionarTodosFundos();" style="font-size: 0.8rem!Important;">Limpar
                                        seleção e mostrar todos</button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-footer bg-white text-right">



                </div>

            </div>

        </div> --}}

        {!! Form::close() !!}

    </div>

    <script>
        function consultar(origem, partidos, casa, ufs) {

            if (origem == 'partido-pdf') {
                window.open("{{ url('relatorios/carometro/partido') }}/" + partidos + "/" + casa + "/" + ufs, "_blank");
            }

            if (origem == 'uf-pdf') {
                window.open("{{ url('relatorios/carometro/uf') }}/" + partidos + "/" + casa + "/" + ufs, "_blank");
            }

            if (origem == 'partido-word') {
                window.open("{{ url('relatorios/carometro/partido-word') }}/" + partidos + "/" + casa + "/" + ufs,
                "_blank");
            }

            if (origem == 'uf-word') {
                window.open("{{ url('relatorios/carometro/uf-word') }}/" + partidos + "/" + casa + "/" + ufs, "_blank");
            }

        }
    </script>
@endsection
