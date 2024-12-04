<div class="row form-group multiple-form-group input-group pt-2">

    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 mb-4 text-left">

        <label for="" class="form-label">Cargo do(a) convidado(a)</label>

        {!! Form::select(
            'convidado[cod_interlocutor][]',
            ${'cod_interlocutor_pluck'},
            $columnName === 'cod_interlocutor' ? $valueDefaultCodInterlocutor : null,
            [
                'class' => 'form-control text-dark',
                'style' => 'cursor: pointer; width: 100% !Important;',
                'id' => 'cod_interlocutor_convidado',
                'autocomplete' => 'off',
                'placeholder' => 'Selecione',
            ],
        ) !!}

        <script type="text/javascript">
            $(document).ready(function() {
                $('#cod_interlocutor_convidado').select2();
                $(document).on("select2:open", () => {
                    document.querySelector(".select2-container--open .select2-search__field").focus()
                });
            });
        </script>

    </div>

    <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6 mb-4 text-left">

        <label for="" class="form-label">Nome convidado(a)</label>

        {!! Form::text('convidado[nom_convidado][]', null, [
            'class' => 'form-control text-dark',
            'id' => 'nom_convidado',
            'placeholder' => 'Digite o nome do(a) convidado(a)',
            'autocomplete' => 'off',
        ]) !!}

    </div>

    <div id="buttonsFileAddRemove" class="col-xs-12 col-sm-1 col-md-4 col-lg-1 mb-4 text-left mt-4 tp-4">

        <button type="button" class="btn btn-success btn-add">+</button>


    </div>

</div>
