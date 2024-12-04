<div class="card h-100 mb-3">

    <div class="card-header bg-light-titulo-modal font-numero-pac">
        2. Configuração básica do(a) cliente no sistema
    </div>

    <div class="card-body bg-white border-secondary">

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-7 col-xxl-6 mb-1">

                <label for="email" class="form-label">
                    <span class="badge rounded-pill bg-primary font-numero-pac"
                        style="background-color: #e79315!Important;">2.1</span>
                    Perfil de acesso
                </label>

                @error('cod_perfil')
                    @php
                        $codPerfilIsInvalid = 'is-invalid';
                    @endphp
                @else
                    @php
                        $codPerfilIsInvalid = '';
                    @endphp
                @enderror

                {!! Form::select(
                    'cod_perfil',
                    $getPluckPerfil,
                    !empty($usuario->cod_perfil) ? $usuario->cod_perfil : '82905238-4f6b-4c92-8903-88db86f90e5b',
                    [
                        'class' => 'form-control text-dark ' . $codPerfilIsInvalid,
                        'style' => 'cursor: pointer; width: 100% !Important;',
                        'id' => 'cod_perfil',
                        'autocomplete' => 'off',
                        'required' => 'required',
                    ],
                ) !!}


                @error('cod_perfil')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <div class="mt-2">
                    @foreach ($getPerfil as $perfil)
                        <p class="m-0 p-0 pl-1" style="font-size: 0.75rem !Important;">
                            <span class="text-bold">{{ $perfil->nom_perfil }}</span> - {{ $perfil->dsc_perfil }}
                        </p>
                    @endforeach
                </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-5 col-xl-5 col-xxl-6 mb-1">

                <label for="email" class="form-label">
                    <span class="badge rounded-pill bg-primary font-numero-pac"
                        style="background-color: #e79315!Important;">2.2</span>
                    Cadastro Ativo ou Inativo?
                </label>

                @error('ativo')
                    @php
                        $atrAtivoIsInvalid = 'is-invalid';
                    @endphp
                @else
                    @php
                        $atrAtivoIsInvalid = '';
                    @endphp
                @enderror

                @if (isset($usuario) && $usuario->ativo)
                    @php
                        $ativo = 'Ativo';
                    @endphp
                @else
                    @php
                        $ativo = 'Inativo';
                    @endphp
                @endif

                {!! Form::select('ativo', [1 => 'Ativo', 0 => 'Inativo'], isset($usuario) ? $usuario->ativo : 1, [
                    'class' => 'form-control text-dark ' . $atrAtivoIsInvalid,
                    'style' => 'cursor: pointer; width: 100% !Important;',
                    'id' => 'ativo',
                    'placeholder' => 'Selecione',
                    'autocomplete' => 'off',
                ]) !!}

                <div id="passwordHelpBlock" class="form-text text-danger">
                    Quando o cadastro deste cliente for inativado, ele não terá mais acesso a nenhuma parte do
                    sistema.
                </div>

                @error('ativo')
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
