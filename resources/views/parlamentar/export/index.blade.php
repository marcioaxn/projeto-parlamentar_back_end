<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base_{{ date('d-m-Y') }}</title>

</head>

<body>

    <table style="font-family: Calibri, Arial;">
        <thead>
            <tr>
                <th style="background-color: #F9F9F9; color: #001030; width: 100px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Código</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 156px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Cargo</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 275px; height: 45px; padding-left: 10px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Nome parlamentar</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 295px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Nome civil</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 156px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Situação</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 275px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Legislatura</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 156px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Formação (TSE)</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 295px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Última ocupação (TSE)</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 125px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Aniversário</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 275px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Cidade natal</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 295px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Celular</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 156px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Telefone</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 275px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>E-mail</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 295px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Lideranças e cargos</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 347px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Comissões</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 100px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Partido</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 131px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>UF de representação</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 100px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Ano eleição</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 92px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>Reeleito</strong>
                </th>
                <th style="background-color: #F9F9F9; color: #001030; width: 100px; height: 45px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>TSE votos</strong>
                </th>
            </tr>
        </thead>

        <tbody>

            @foreach ($parlamentares as $parlamentar)
                @php
                    // Início de recuperar a legislatura dos Deputados Federais
                    $legislaturas = null;
                    if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {
                        if ($parlamentar->legislaturasDeputado->count() > 0) {
                            foreach ($parlamentar->legislaturasDeputado as $legislatura) {
                                $legislaturas .= $legislatura->legislatura . '/';
                            }

                            $legislaturas = trim($legislaturas, '/');
                        }
                    }
                    // Fim de recuperar a legislatura dos Deputados Federais

                    // Início de recuperar a legislatura dos Deputados Federais
                    if ($parlamentar->dsc_casa === 'Senado Federal') {
                        if ($parlamentar->legislaturasSenado->count() > 0) {
                            foreach ($parlamentar->legislaturasSenado as $legislatura) {
                                $legislaturas .= $legislatura->legislatura . '/';
                            }

                            $legislaturas = trim($legislaturas, '/');
                        }
                    }
                    // Fim de recuperar a legislatura dos Deputados Federais

                    // Início de recuperar o celular
                    $celulares = null;
                    if (Session::get('permissao') === '0010000' || Session::get('permissao') === '0000100') {
                        if ($parlamentar->celulares->count() > 0) {
                            $contCelular = 1;
                            foreach ($parlamentar->celulares as $celular) {
                                if ($contCelular <= 4) {
                                    $celulares .= applyMask($celular->num_celular, '(##) #####-####') . ' / ';
                                }
                                $contCelular++;
                            }

                            $celulares = trim($celulares, ' / ');
                        }
                    }
                    // Fim de recuperar o celular

                    // Início de recuperar o número de telefone do gabinete
                    $telefoneGabinete = null;
                    if ($parlamentar->num_telefone != '') {
                        if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {
                            $telefoneGabinete = '(61) ' . $parlamentar->num_telefone;
                        }

                        if ($parlamentar->dsc_casa === 'Senado Federal') {
                            $telefoneGabinete = applyMask('61' . $parlamentar->num_telefone, '(##) ####-####');
                        }
                    }
                    // Fim de recuperar o número de telefone do gabinete

                    // Início de recuperar o e-mail do gabinete do parlamentar
                    $emailGabienete = null;
                    if ($parlamentar->dsc_email != '') {
                        $emailGabienete = $parlamentar->dsc_email;
                    }
                    // Fim de recuperar o e-mail do gabinete do parlamentar

                    // Início para verificar se o deputado federal exercer alguma liderança
                    $cargosLiderancas = null;
                    // Senadores
                    if ($parlamentar->dsc_casa === 'Senado Federal') {
                        $cargosMesaDiretora = null;

                        if ($parlamentar->cargosMesaDiretoraSenado) {
                            $cargosMesaDiretora = $parlamentar->cargosMesaDiretoraSenado->Cargo;

                            if ($cargosMesaDiretora === 'PRESIDENTE') {
                                $cargosMesaDiretora = 'PRESIDENTE DO SENADO FEDERAL';
                            } else {
                                $cargosMesaDiretora = $cargosMesaDiretora . ' DA MESA DO SENADO';
                            }
                        }

                        if ($parlamentar->liderancaSenadores) {
                            $contLideranca = 1;

                            foreach ($parlamentar->liderancaSenadores as $key => $lideranca) {
                                if (
                                    isset($lideranca->SiglaPartido) &&
                                    !is_null($lideranca->SiglaPartido) &&
                                    $lideranca->SiglaPartido != ''
                                ) {
                                    $cargosLiderancas .=
                                        $contLideranca .
                                        '. ' .
                                        retornaTextoTirandoParteDoTexto(
                                            alterarDescricaoLideranca($lideranca->DescricaoTipoLideranca),
                                            ' do Senado Federal',
                                        );
                                    $cargosLiderancas .=
                                        ' do ' .
                                        retornaTextoTirandoParteDoTexto(
                                            $lideranca->SiglaPartido,
                                            'Congresso Nacional',
                                        ) .
                                        ' no ' .
                                        $lideranca->SiglaCasaLideranca;
                                    $cargosLiderancas .= '; ';
                                } else {
                                    $cargosLiderancas .= $contLideranca . '. ' . $lideranca->UnidadeLideranca;

                                    isset($lideranca->NomeBloco) &&
                                    !is_null($lideranca->NomeBloco) &&
                                    $lideranca->NomeBloco != ''
                                        ? ($cargosLiderancas .= 'do ' . $lideranca->NomeBloco)
                                        : '';
                                    $cargosLiderancas .= '; ';
                                }

                                $contLideranca++;
                            }

                            foreach ($parlamentar->cargosSenadores as $cargo) {
                                if (!is_null($cargo->colegiadoAtivo)) {
                                    $cargosLiderancas .=
                                        $contLideranca .
                                        '. ' .
                                        primeiraLetraMaiuscula($cargo->DescricaoCargo) .
                                        ' do(a) ' .
                                        $cargo->SiglaComissao;
                                    $cargosLiderancas .= '; ';
                                    $contLideranca++;
                                }
                            }

                            $cargosLiderancas = trim($cargosLiderancas, '; ');
                        }
                    }

                    // Deputados federais
                    if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {
                        $cargosMesaDiretora = null;

                        if ($parlamentar->cargosMesaDiretora) {
                            $cargosMesaDiretora = $parlamentar->cargosMesaDiretora->titulo;

                            if ($cargosMesaDiretora === 'Presidente') {
                                $cargosMesaDiretora = 'PRESIDENTE DA CÂMARA DOS DEPUTADOS';
                            } else {
                                $cargosMesaDiretora = $cargosMesaDiretora . ' DA MESA DIRETORA';
                            }
                        }

                        if ($parlamentar->liderancaDeputados) {
                            $contLideranca = 1;

                            foreach ($parlamentar->liderancaDeputados as $key => $lideranca) {
                                $cargosLiderancas .=
                                    $contLideranca .
                                    '. ' .
                                    $lideranca->titulo .
                                    ' do(a) ' .
                                    alterarDescricaoLideranca($lideranca->tipo);
                                $lideranca->nome != $lideranca->tipo
                                    ? ($cargosLiderancas .= alterarDescricaoLideranca($lideranca->nome))
                                    : '';

                                $contLideranca++;
                            }

                            $cargosLiderancas = trim($cargosLiderancas, ', ');
                        }
                    }
                    // Fim para verificar se o deputado federal exercer alguma liderança

                    // Início de recuperar as comissões onde é titular
                    $comissoes = null;
                    if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {
                        if ($parlamentar->comissoesDeputados->count() > 0) {
                            foreach ($parlamentar->comissoesDeputados as $comissao) {
                                if (substr($comissao->siglaOrgao, 0, 1) === 'C') {
                                    $comissoes .= $comissao->siglaOrgao . ', ';
                                }
                            }

                            $comissoes = trim($comissoes, ', ');
                        }
                    }

                    if ($parlamentar->dsc_casa === 'Senado Federal') {
                        if ($parlamentar->comissoesSenadores->count() > 0) {
                            foreach ($parlamentar->comissoesSenadores as $comissao) {
                                if (substr($comissao->SiglaComissao, 0, 1) === 'C') {
                                    $comissoes .= $comissao->SiglaComissao . ', ';
                                }
                            }

                            $comissoes = trim($comissoes, ', ');
                        }
                    }
                    // Fim de recuperar as comissões onde é titular
                @endphp

                <tr>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ $parlamentar->cod_parlamentar }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ $parlamentar->dsc_tratamento }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ primeiraLetraMaiuscula($parlamentar->nom_parlamentar_sem_formatacao) }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ primeiraLetraMaiuscula($parlamentar->nom_parlamentar_completo) }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ $parlamentar->dsc_participacao . ' - ' . $parlamentar->dsc_situacao }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ $legislaturas }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ $parlamentar->resumo ? primeiraLetraMaiuscula($parlamentar->resumo->ds_grau_instrucao) : '-' }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ $parlamentar->resumo ? primeiraLetraMaiuscula($parlamentar->resumo->ds_ocupacao) : '-' }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ formatarDataComCarbonParaBR($parlamentar->dte_nascimento) }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ primeiraLetraMaiuscula($parlamentar->nom_municipio_nascimento) . '/' . $parlamentar->sgl_uf_nascimento }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        @if (Session::get('permissao') === '0010000' || Session::get('permissao') === '0000100')
                            {{ $celulares }}
                        @endif
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        @php
                            if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {
                                print $parlamentar->num_telefone;
                            } else {
                                print applyMask($parlamentar->num_telefone, '####-####');
                            }
                        @endphp
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ strtolower(limpaStringSemTirarHifem($emailGabienete)) }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ isset($cargosMesaDiretora) && !empty($cargosMesaDiretora) ? $cargosMesaDiretora . ', ' : null }}
                        {{ isset($cargosLiderancas) && !empty($cargosLiderancas) ? $cargosLiderancas : null }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ $comissoes }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ $parlamentar->sgl_partido }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        {{ $parlamentar->sgl_uf_representante }}
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        @php
                            isset($parlamentar->num_ano_eleicao) &&
                            !is_null($parlamentar->num_ano_eleicao) &&
                            $parlamentar->num_ano_eleicao != ''
                                ? print $parlamentar->num_ano_eleicao
                                : print '-';
                        @endphp
                    </td>
                    <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;" valign="center">
                        @php
                            isset($parlamentar->dsc_reeleito) &&
                            !is_null($parlamentar->dsc_reeleito) &&
                            $parlamentar->dsc_reeleito != ''
                                ? print $parlamentar->dsc_reeleito
                                : print '-';
                        @endphp
                    </td>
                    <td style="border: 1px dotted gray; text-align: right; word-wrap:break-word;" valign="center">
                        @php
                            isset($parlamentar->num_total_votos) &&
                            !is_null($parlamentar->num_total_votos) &&
                            $parlamentar->num_total_votos != ''
                                ? print $parlamentar->num_total_votos
                                : print '-';
                        @endphp
                    </td>
                </tr>
            @endforeach

        </tbody>

    </table>


</body>

</html>
