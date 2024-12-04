<div class="row form-group multiple-form-group input-group pt-2">

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4 text-left">

        <label for="{!! $columnName !!}" class="form-label">Descrição da demanda</label>

        {!! Form::textarea('demadas[dsc_demanda][]', null, [
            'class' => 'form-control',
            'rows' => 1,
            'id' => 'dsc_demanda',
            'placeholder' => 'Digite a descrição da demanda',
            'rows' => 2,
            'cols' => 50,
            'required' => 'required',
        ]) !!}

    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4 text-left">

        <label for="{!! $columnName !!}" class="form-label">Área Responsável pela
            demanda</label>

        {!! Form::select('demadas[codigoUnidade][]', $responsaveisDemanda, null, [
            'id' => 'codigoUnidade',
            'class' => 'form-control',
            'style' => 'cursor: pointer;',
            'placeholder' => 'Selecione',
            'required' => 'required',
        ]) !!}

    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4 text-left">

        <label for="{!! $columnName !!}" class="form-label">Prazo estimado de conclusão da
            demanda</label>

        {!! Form::date('demadas[dte_prazo][]', null, [
            'class' => 'form-control text-dark text-right font-numero date',
            'id' => 'dte_prazo',
            'style' => 'cursor: pointer',
            'autocomplete' => 'off',
            'required' => 'required',
        ]) !!}

    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 mb-4 text-left">

        <label for="{!! $columnName !!}" class="form-label">Status da demanda</label>

        {!! Form::select('demadas[cod_status_demanda][]', $statusDemanda, '9d7705d3-567a-4422-cd0c-151676e8037e', [
            'id' => 'cod_status_demanda',
            'class' => 'form-control',
            'style' => 'cursor: pointer;',
            'required' => 'required',
        ]) !!}

    </div>

    <div id="buttonsAddRemove" class="col-xs-12 col-sm-6 col-md-4 col-lg-1 mb-4 text-left mt-4 tp-4">

        <button type="button" class="btn btn-success btn-add">+</button>


    </div>

</div>
