<div class="card h-100 mb-3">

    <div class="card-header bg-light-titulo-modal font-numero-pac">
        3. Permissões do(a) cliente em cada Módulo do sistema
    </div>

    <div class="card-body bg-white border-secondary">

        <style>
            .form-switch-radio .form-check-input {
                width: 2.5em;
                height: 1.5em;
                background-color: #e9ecef;
                border-radius: 1.25em;
                position: relative;
                appearance: none;
                -webkit-appearance: none;
                cursor: pointer;
                outline: none;
                transition: background-color 0.2s;
            }

            .form-switch-radio .form-check-input::before {
                content: "";
                position: absolute;
                top: 0.25em;
                left: 0.25em;
                width: 1em;
                height: 1em;
                background-color: #fff;
                border-radius: 50%;
                transition: transform 0.2s;
            }

            .form-switch-radio .form-check-input:checked {
                background-color: #0d6efd;
            }

            .form-switch-radio .form-check-input:checked::before {
                transform: translateX(1em);
            }
        </style>

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 table-responsive mb-2">

                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                Módulo
                            </th>

                            @foreach ($getPermissoesModulo as $permissaoModulo)
                                <th>
                                    Nível {{ $permissaoModulo->cod_permissao_modulo }} - {!! $permissaoModulo->dsc_permissao_modulo !!}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $permissao = [];
                        @endphp

                        @if (isset($usuario))
                            @foreach ($usuario->permissoesModulos as $permissaoModulo)
                                @php
                                    $permissao[$permissaoModulo->cod_modulo] = $permissaoModulo->cod_permissao_modulo;
                                @endphp
                            @endforeach
                        @endif

                        @php
                            $contModulo = 1;
                        @endphp

                        @foreach ($getModulos as $modulo)
                            <tr>
                                <th class="text-bold" style="width: 15%;">
                                    <span class="badge rounded-pill bg-danger font-numero-pac"
                                        style="background-color: #696969!Important;">3.{{ $contModulo }}</span>
                                    {{ $modulo->nom_modulo }}
                                </th>

                                @foreach ($getPermissoesModulo as $permissaoModulo)
                                    <th class="pl-4" style="width: 10%;">

                                        <div class="form-check form-switch-radio">
                                            <input class="form-check-input" type="radio"
                                                name="modulos[{{ $modulo->cod_modulo }}]"
                                                id="cod_permissao_modulo_modulo_{{ $permissaoModulo->cod_permissao_modulo }}_{{ $modulo->cod_modulo }}"
                                                value="{{ $permissaoModulo->cod_permissao_modulo }}"
                                                style="font-size: 0.8rem!Important;" <?php !isset($usuario) && $permissaoModulo->cod_permissao_modulo == 1 ? print 'checked' : null; ?>
                                                <?php array_key_exists($modulo->cod_modulo, $permissao) && $permissaoModulo->cod_permissao_modulo == $permissao[$modulo->cod_modulo] ? print 'checked' : ''; ?>>&nbsp;
                                            <label class="form-check-label"
                                                for="cod_permissao_modulo_modulo_{{ $permissaoModulo->cod_permissao_modulo }}_{{ $modulo->cod_modulo }}">
                                                nível {{ $permissaoModulo->cod_permissao_modulo }}
                                            </label>
                                        </div>

                                    </th>
                                @endforeach
                            </tr>

                            @php
                                $contModulo++;
                            @endphp
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="card-footer text-right bg-white">

        <a href="{!! route('dashboard-clientes') !!}" class="btn btn-outline-secondary btn-sm">Voltar</a>

        <button type="submit" class="btn btn-outline-primary btn-sm">Salvar</button>

    </div>

</div>
