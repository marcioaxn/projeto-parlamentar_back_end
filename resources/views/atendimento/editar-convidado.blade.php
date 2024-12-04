<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 text-left">
    <hr>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left collapse" id="divColRetorno_convidado">
    <div class="alert alert-success alert-dismissible d-flex align-items-center mb-3 fade show" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
            <use xlink:href="#check-circle-fill" />
        </svg>
        <div>
            O sistema gravou com sucesso a alteração feita na parte de convidados.
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left collapse" id="divColRetorno_incluir_convidado">
    <div class="alert alert-success alert-dismissible d-flex align-items-center mb-3 fade show" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
            <use xlink:href="#check-circle-fill" />
        </svg>
        <div>
            O sistema gravou com sucesso a inclusão de novo(a) convidado(a).
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left collapse" id="divColRetorno_excluir_convidado">
    <div class="alert alert-success alert-dismissible d-flex align-items-center mb-3 fade show" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
            <use xlink:href="#check-circle-fill" />
        </svg>
        <div>
            O sistema excluiu com sucesso o(a) convidado(a).
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

@php
    // Início da parte de incluir um novo convidado
@endphp

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left">
    <label for="" class="form-label tituloItens">Convidados</label>

    <div class="row form-group multiple-form-group input-group pt-2">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left text-bold">Incluir
            novo(a) Convidado(a)</div>

        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 mb-4 text-left">

            <label for="" class="form-label">Cargo do(a) convidado(a)</label>

            {!! Form::select(
                'convidado[cod_interlocutor][]',
                ${'cod_interlocutor_pluck'},
                $columnName === 'cod_interlocutor' ? $valueDefaultCodInterlocutor : null,
                [
                    'class' => 'form-control text-dark',
                    'style' => 'cursor: pointer; width: 100% !Important;',
                    'id' => 'cod_interlocutor_convidado_novo',
                    'autocomplete' => 'off',
                    'placeholder' => 'Selecione',
                ],
            ) !!}

            <script type="text/javascript">
                $(document).ready(function() {
                    $('#cod_interlocutor_convidado_novo').select2();
                    $(document).on("select2:open", () => {
                        document.querySelector(".select2-container--open .select2-search__field").focus()
                    });
                });
            </script>

        </div>

        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 mb-4 text-left">

            <label for="" class="form-label">Nome convidado(a)</label>

            {!! Form::text('convidado[nom_convidado][]', null, [
                'class' => 'form-control text-dark',
                'id' => 'nom_convidado_novo',
                'placeholder' => 'Digite o nome do(a) convidado(a)',
                'autocomplete' => 'off',
                'required' => 'required',
            ]) !!}

        </div>

        <div class="col-1 col-sm-1 col-md-2 col-lg-2 mb-4 pb-4 text-left"
            style="padding-top: 2.1rem !Important; padding-left: 0rem !Important;">

            <button type="button" class="btn btn-success btn-sm"
                onclick="javascript: salvar_convidados(document.getElementById('cod_interlocutor_convidado_novo').value, document.getElementById('nom_convidado_novo').value, '{{ $codAtendimento }}');">
                <i class="fas fa-save text-white"></i> Salvar convidado
            </button>

        </div>

    </div>

</div>

@php
    // Fim da parte de incluir um novo convidado
@endphp

<div id="divConvidados" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    @php
        $contConvidado = 1;
    @endphp

    @foreach ($atendimento->convidados as $convidado)
        <form id="formEditarConvidado{{ $convidado->cod_convidado }}" class="" method="post">
            <div class="row">
                {{ csrf_field() }}
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left text-bold"><span
                        class="font-numero">{{ $contConvidado }}</span>º
                    Convidado(a) <i class="fas fa-eye pointer text-primary" data-bs-toggle="modal"
                        data-bs-target="#modalLog{{ 'Convidados' . $convidado->cod_convidado }}"></i>
                    {!! app(App\Http\Controllers\TabAtendimentosController::class)->modalTabelaLog(
                        'Convidados' . $convidado->cod_convidado,
                        $convidado->auditoria->count() .
                            ' ação(ões) realizada(s) no <span
                                                                class="font-numero">' .
                            $contConvidado .
                            '</span>º convidado',
                        $convidado->auditoria,
                    ) !!}
                </div>
                <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 mb-4 text-left">

                    <label for="" class="form-label">Cargo do(a) convidado(a)</label>

                    {!! Form::select('cod_interlocutor', ${'cod_interlocutor_pluck'}, $convidado->cod_interlocutor, [
                        'class' => 'form-control text-dark',
                        'style' => 'cursor: pointer; width: 100% !Important;',
                        'id' => 'cod_interlocutor_convidado_' . $convidado->cod_convidado,
                        'autocomplete' => 'off',
                        'placeholder' => 'Selecione',
                    ]) !!}

                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#cod_interlocutor_convidado_{{ $convidado->cod_convidado }}').select2();
                            $(document).on("select2:open", () => {
                                document.querySelector(".select2-container--open .select2-search__field").focus()
                            });
                        });
                    </script>

                </div>

                <div class="col-11 col-sm-5 col-md-3 col-lg-3 text-left">

                    <label for="" class="form-label">Nome convidado(a)</label>

                    {!! Form::text('nom_convidado', $convidado->nom_convidado, [
                        'class' => 'form-control text-dark',
                        'id' => 'nom_convidado',
                        'placeholder' => 'Digite o nome do(a) convidado(a)',
                        'autocomplete' => 'off',
                    ]) !!}

                </div>

                <div id="buttonsFileAddRemove" class="col-1 col-sm-1 col-md-4 col-lg-4 m-0 mb-4 p-0 pb-4 text-left"
                    style="padding-top: 2.1rem !Important; padding-left: 0rem !Important;">

                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-save text-white"></i> Confirmar alteração
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#modalConfirmarExclusaoConvidado_{{ $convidado->cod_convidado }}">
                        <i class="fas fa-trash-alt"></i> Excluir convidado
                    </button>

                </div>

                @php
                    // Início da modal de confirmação de exclusão do convidado
                @endphp

                <div class="modal" id="modalConfirmarExclusaoConvidado_{{ $convidado->cod_convidado }}" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
                    data-bs-keyboard="false" style="padding-top: 150px!Important;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header"
                                style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                <p class="modal-title text-white"
                                    style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                    Excluir Convidado</p>
                            </div>
                            <div class="modal-body">

                                <p>
                                    Deseja realmente excluir este convidado?
                                </p>

                                <p>
                                    <span class="text-bold">{{ $convidado->nom_convidado }}</span> /
                                    <span class="text-bold">{{ $convidado->interlocutor->dsc_interlocutor }}</span>
                                </p>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fechar</button>
                                <button class="btn btn-danger btn-sm"
                                    onclick="javascript: excluir_convidado('{{ $convidado->cod_convidado }}', '{{ $codAtendimento }}');"
                                    data-bs-dismiss="modal">Sim, quero excluir!</button>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    // Fim da modal de confirmação de exclusão do convidado
                @endphp

                @php
                    $contConvidado++;
                @endphp

            </div>

            {!! Form::hidden('cod_convidado', $convidado->cod_convidado) !!}
            {!! Form::hidden('cod_atendimento', $codAtendimento) !!}

        </form>

        <script>
            $(document).ready(function() {
                $("#formEditarConvidado{{ $convidado->cod_convidado }}").submit(function(event) {

                    event.preventDefault();
                    var formData = new FormData(this);

                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content")
                        },
                        url: "{{ url('atendimento/convidado/update') }}",
                        data: formData,
                        type: "post",
                        async: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {

                            setTimeout(function() {
                                $("#divColRetorno_convidado").fadeIn("slow");
                            }, 100);

                            setTimeout(function() {
                                $("#divColRetorno_convidado").fadeOut("slow");
                            }, 3900);

                        }
                    });

                });
            });
        </script>
    @endforeach

</div>
