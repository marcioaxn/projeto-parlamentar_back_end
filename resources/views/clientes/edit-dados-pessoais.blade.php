<div class="card h-100 mb-3">

    <div class="card-header bg-light-titulo-modal font-numero-pac">1. Dados pessoais e lotação <?php isset($usuario) ? print ' do(a) <span class="text-bold">' . $usuario->name . '</span>' : null; ?>
    </div>

    <div class="card-body bg-white border-secondary">

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-2">

                <label for="name" class="form-label">
                    <span class="badge rounded-pill bg-primary font-numero-pac"
                        style="background-color: #094a85!Important;">1.1</span>
                    Nome
                </label>

                {!! Form::text('name', isset($usuario) ? $usuario->name : null, [
                    'class' => 'form-control',
                    'id' => 'name',
                    'autocomplete' => 'off',
                    'required' => 'required',
                ]) !!}

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-2">

                <label for="email" class="form-label">
                    <span class="badge rounded-pill bg-primary font-numero-pac"
                        style="background-color: #094a85!Important;">1.2</span>
                    E-mail institucional
                </label>

                {!! Form::email('email', isset($usuario) ? $usuario->email : null, [
                    'class' => 'form-control',
                    'id' => 'email',
                    'autocomplete' => 'off',
                    'required' => 'required',
                ]) !!}

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-2">

                <label for="email" class="form-label">
                    <span class="badge rounded-pill bg-primary font-numero-pac"
                        style="background-color: #094a85!Important;">1.3</span>
                    Lotação de exercício <span class="text-muted small">(SIORG)</span>
                </label>

                @error('codigoUnidade')
                    @php
                        $codUnidadeIsInvalid = 'is-invalid';
                    @endphp
                @else
                    @php
                        $codUnidadeIsInvalid = '';
                    @endphp
                @enderror

                {!! Form::select('codigoUnidade', $getPluckOrganizacao, isset($usuario) ? $usuario->codigoUnidade : null, [
                    'class' => 'form-control text-dark ' . $codUnidadeIsInvalid,
                    'style' => 'cursor: pointer; width: 100% !Important;',
                    'id' => 'codigoUnidade',
                    'autocomplete' => 'off',
                    'placeholder' => 'Selecione',
                    'required' => 'required',
                ]) !!}

                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#codigoUnidade').select2();
                        $(document).on("select2:open", () => {
                            document.querySelector('.select2-container--open .select2-search__field').focus();
                        });
                    });
                </script>

                @error('codigoUnidade')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

            </div>

        </div>

    </div>

    <div class="card-footer text-right bg-white">

        <a href="{!! route('dashboard-clientes') !!}" class="btn btn-outline-secondary btn-sm">Voltar</a>

        <button type="submit" class="btn btn-outline-primary btn-sm">Salvar</button>

    </div>

</div>
