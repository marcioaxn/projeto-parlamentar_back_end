<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 text-left">
    <hr>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left collapse" id="divColRetorno_demadas">
    <div class="alert alert-success alert-dismissible d-flex align-items-center mb-3 fade show" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
            <use xlink:href="#check-circle-fill" />
        </svg>
        <div>
            O sistema gravou com sucesso a alteração feita na parte de demandas.
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left collapse" id="divColRetorno_incluir_demandas">
    <div class="alert alert-success alert-dismissible d-flex align-items-center mb-3 fade show" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
            <use xlink:href="#check-circle-fill" />
        </svg>
        <div>
            O sistema gravou com sucesso a inclusão de nova demanda recebida.
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left collapse" id="divColRetorno_excluir_demandas">
    <div class="alert alert-success alert-dismissible d-flex align-items-center mb-3 fade show" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
            <use xlink:href="#check-circle-fill" />
        </svg>
        <div>
            O sistema excluiu com sucesso a demanda recebida.
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left">
    <label for="" class="form-label tituloItens">Demandas recebidas</label>

    <div class="row form-group multiple-form-group input-group p-0 pt-2">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left text-bold">Incluir nova
            Demanda
            Recebida</div>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4 text-left">

            <label for="{!! $columnName !!}" class="form-label">Descrição da demanda</label>

            {!! Form::textarea('demadas[dsc_demanda][]', null, [
                'class' => 'form-control',
                'rows' => 1,
                'id' => 'dsc_demanda_nova',
                'placeholder' => 'Digite a descrição da demanda',
                'rows' => 2,
                'cols' => 50,
                'required' => 'required',
            ]) !!}

        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 mb-4 text-left">

            <label for="{!! $columnName !!}" class="form-label">Área Responsável pela
                demanda</label>

            {!! Form::select('demadas[codigoUnidade][]', $responsaveisDemanda, null, [
                'id' => 'codigoUnidade_nova',
                'class' => 'form-control',
                'style' => 'cursor: pointer;',
                'placeholder' => 'Selecione',
                'required' => 'required',
            ]) !!}

        </div>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4 text-left">

            <label for="{!! $columnName !!}" class="form-label">Prazo estimado de concluir
                a
                demanda</label>

            {!! Form::date('demadas[dte_prazo][]', null, [
                'class' => 'form-control text-dark text-right font-numero date',
                'id' => 'dte_prazo_nova',
                'style' => 'cursor: pointer',
                'autocomplete' => 'off',
                'required' => 'required',
            ]) !!}

        </div>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 mb-4 text-left">

            <label for="{!! $columnName !!}" class="form-label">Status da demanda</label>

            {!! Form::select('demadas[cod_status_demanda][]', $statusDemanda, '9d7705d3-567a-4422-cd0c-151676e8037e', [
                'id' => 'cod_status_demanda_nova',
                'class' => 'form-control',
                'style' => 'cursor: pointer;',
                'required' => 'required',
            ]) !!}

        </div>

        <div class="col-1 col-sm-1 col-md-2 col-lg-2 mb-4 pb-4 text-left"
            style="padding-top: 2.1rem !Important; padding-left: 0rem !Important;">

            <button type="button" class="btn btn-success btn-sm"
                onclick="javascript: salvar_demanda(document.getElementById('dsc_demanda_nova').value, document.getElementById('codigoUnidade_nova').value, document.getElementById('dte_prazo_nova').value, document.getElementById('cod_status_demanda_nova').value, '{{ $codAtendimento }}');">
                <i class="fas fa-save text-white"></i> Salvar demanda
            </button>

        </div>

    </div>


</div>

@php
    $contDemanda = 1;
@endphp

<div id="divDemandas" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    @foreach ($atendimento->demandas as $demanda)
        <form id="formEditarDemanda{{ $demanda->cod_demanda_atendimento }}" class="" method="post">
            <div class="row">
                {{ csrf_field() }}
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left text-bold">
                    <span class="font-numero">{{ $contDemanda }}</span>ª
                    Demanda recebida <i class="fas fa-eye pointer text-primary" data-bs-toggle="modal"
                        data-bs-target="#modalLog{{ 'Demandas' . $demanda->cod_demanda_atendimento }}"></i>
                    {!! app(App\Http\Controllers\TabAtendimentosController::class)->modalTabelaLog(
                        'Demandas' . $demanda->cod_demanda_atendimento,
                        $demanda->auditoria->count() . ' ação(ões) realizada(s) na Demanda',
                        $demanda->auditoria,
                    ) !!}
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4 text-left">

                    <label for="{!! $columnName !!}" class="form-label">Descrição da
                        demanda</label>

                    {!! Form::textarea('dsc_demanda', $demanda->dsc_demanda, [
                        'class' => 'form-control',
                        'rows' => 1,
                        'id' => 'dsc_demanda',
                        'placeholder' => 'Digite a descrição da demanda',
                        'rows' => 2,
                        'cols' => 50,
                    ]) !!}

                </div>

                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 mb-4 text-left">

                    <label for="{!! $columnName !!}" class="form-label">Área Responsável
                        pela
                        demanda</label>

                    {!! Form::select('codigoUnidade', $responsaveisDemanda, $demanda->codigoUnidade, [
                        'id' => 'codigoUnidade',
                        'class' => 'form-control',
                        'style' => 'cursor: pointer;',
                        'placeholder' => 'Selecione',
                    ]) !!}

                </div>

                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 mb-4 text-left">

                    <label for="{!! $columnName !!}" class="form-label">Prazo estimado de
                        concluir
                        a
                        demanda</label>

                    {!! Form::date('dte_prazo', $demanda->dte_prazo, [
                        'class' => 'form-control text-dark text-right font-numero date',
                        'id' => 'dte_prazo',
                        'style' => 'cursor: pointer',
                        'autocomplete' => 'off',
                    ]) !!}

                </div>

                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 mb-4 text-left">

                    <label for="{!! $columnName !!}" class="form-label">Status da
                        demanda</label>

                    {!! Form::select('cod_status_demanda', $statusDemanda, $demanda->cod_status_demanda, [
                        'id' => 'cod_status_demanda',
                        'class' => 'form-control',
                        'style' => 'cursor: pointer;',
                    ]) !!}

                </div>

                <div id="buttonsFileAddRemove" class="col-1 col-sm-1 col-md-2 col-lg-3 mb-4 pb-4 text-left"
                    style="padding-top: 2.1rem !Important; padding-left: 0rem !Important;">

                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-save text-white"></i> Confirmar alteração
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#modalConfirmarExclusaoDemanda_{{ $demanda->cod_demanda_atendimento }}">
                        <i class="fas fa-trash-alt"></i> Excluir demanda
                    </button>

                </div>

                @php
                    // Início da modal de confirmação de exclusão do convidado
                @endphp

                <div class="modal" id="modalConfirmarExclusaoDemanda_{{ $demanda->cod_demanda_atendimento }}"
                    tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
                    data-bs-keyboard="false" style="padding-top: 150px!Important;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header"
                                style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                <p class="modal-title text-white"
                                    style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                    Excluir Demanda</p>
                            </div>
                            <div class="modal-body">

                                <p>
                                    Deseja realmente excluir esta demanda recebida?
                                </p>

                                <p>
                                    <span class="text-bold">{{ $demanda->dsc_demanda }}</span>
                                </p>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Fechar</button>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="javascript: excluir_demanda('{{ $demanda->cod_demanda_atendimento }}', '{{ $codAtendimento }}');"
                                    data-bs-dismiss="modal">Sim, quero excluir!</button>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    // Fim da modal de confirmação de exclusão do convidado
                @endphp

                @php
                    $contDemanda++;
                @endphp

            </div>

            {!! Form::hidden('cod_demanda_atendimento', $demanda->cod_demanda_atendimento) !!}
            {!! Form::hidden('cod_atendimento', $codAtendimento) !!}

        </form>

        <script>
            $(document).ready(function() {
                $("#formEditarDemanda{{ $demanda->cod_demanda_atendimento }}").submit(function(event) {

                    event.preventDefault();
                    var formData = new FormData(this);

                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content")
                        },
                        url: "{{ url('atendimento/demanda/update') }}",
                        data: formData,
                        type: "post",
                        async: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {

                            setTimeout(function() {
                                $("#divColRetorno_demadas").fadeIn("slow");
                            }, 100);

                            setTimeout(function() {
                                $("#divColRetorno_demadas").fadeOut("slow");
                            }, 3900);

                        }
                    });

                });
            });
        </script>
    @endforeach

</div>
