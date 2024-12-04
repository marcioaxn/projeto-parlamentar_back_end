<div class="row form-group multiple-form-group input-group pt-2">

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4 text-left">

        <label for="" class="form-label">Assunto do arquivo</label>

        {!! Form::text('anexos[txt_assunto][]', null, [
            'class' => 'form-control',
            'rows' => 1,
            'id' => 'txt_assunto',
            'placeholder' => 'Digite o assunto do teor do arquivo',
        ]) !!}

    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4 text-left">

        <label for="" class="form-label">Arquivo</label>

        {!! Form::file('anexos[arquivo][]', null, [
            'class' => 'form-control anexos',
            'rows' => 1,
            'id' => 'arquivoInput',
            'placeholder' => 'Selecione o arquivo',
        ]) !!}

    </div>

    <div id="buttonsFileAddRemove" class="col-xs-12 col-sm-6 col-md-4 col-lg-1 mb-4 text-left mt-4 tp-4">
        <button type="button" class="btn btn-success btn-add">+</button>
    </div>

</div>

<div id="erros"></div>
