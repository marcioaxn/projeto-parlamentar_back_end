@if (count($value['historico']) > 0)
    @php
        $auditColumn =
            '<i class="fas fa-eye pointer text-danger" data-bs-toggle="modal" data-bs-target="#modalLog' .
            $value['colunm_name'] .
            '"></i>';

        $auditColumn .=
            '<div class="modal fade" id="modalLog' .
            $value['colunm_name'] .
            '" aria-labelledby="exampleModalLabel"
                                                                        aria-hidden="true" style="padding-top: 119px!Important;">
                                                                            <div class="modal-dialog modal-xl">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header" style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                                                                        <p class="modal-title text-white" style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                                                            <i class="fas fa-eye"></i> Ações realizadas no campo <span class="text-bold">' .
            nomeCampoTabNovoPacNormalizado($value['colunm_name']) .
            '</span></p>
                                                                                    </div>
                                                                                    <div class="modal-body mt-0 pt-0" style="max-height: 65vh; overflow-y: auto;width: 100%!Important;">';

        $auditColumn .= '<table class="table" style="width: 100%!Important;">
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                        <th scope="col" class="text-right text-bold">#</th>
                                                                                                        <th scope="col" class="text-bold">Antes da alteração</th>
                                                                                                        <th scope="col" class="text-bold">Depois da alteração</th>
                                                                                                        <th scope="col" class="text-bold">Quando</th>
                                                                                                        <th scope="col" class="text-bold">Quem</th>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>';

        foreach ($value['historico'] as $historico) {
            $contLog = count($historico);
            foreach ($historico as $valueDetalheHistorico) {
                if ($value['data_type'] === 'numeric' || $value['data_type'] === 'double precision') {
                    $valueDetalheHistorico['antes'] = converteValor('MYSQL', 'PTBR', $valueDetalheHistorico['antes']);
                    $valueDetalheHistorico['depois'] = converteValor('MYSQL', 'PTBR', $valueDetalheHistorico['depois']);
                }

                if ($value['data_type'] === 'date') {
                    $valueDetalheHistorico['antes'] = converterData('EN', 'PTBR', $valueDetalheHistorico['antes']);
                    $valueDetalheHistorico['depois'] = converterData('EN', 'PTBR', $valueDetalheHistorico['depois']);
                }

                $auditColumn .=
                    '<tr>
                                                                                                                <td class="text-right font-numero" style="font-size: 0.8rem!Important;">' .
                    $contLog .
                    '</td>
                                                                                                                <td class="font-numero" style="font-size: 0.8rem!Important;">' .
                    $valueDetalheHistorico['antes'] .
                    '</td>
                                                                                                                <td class="font-numero" style="font-size: 0.8rem!Important;">' .
                    $valueDetalheHistorico['depois'] .
                    '</td>
                                                                                                                <td class="font-numero" style="font-size: 0.8rem!Important;">' .
                    $valueDetalheHistorico['created_at'] .
                    '</td>
                                                                                                                <td class="font-numero" style="font-size: 0.8rem!Important;">' .
                    $valueDetalheHistorico['quem'] .
                    '</td></tr>';

                $contLog--;
            }
        }

        $auditColumn .= '</tbody>
                                                                                            </table>';

        $auditColumn .= '</div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Fechar</button>
                                                                                    </div>
                                                                                </div>
                                                                        </div>
                                                                    </div>';
    @endphp
@else
    @php
        $auditColumn = '';
    @endphp
@endif


<label for="exampleFormControlInput1" class="form-label">
    <span class="badge rounded-pill {{ $bgNumeroLabel }} font-numero-pac">{{ substr($key, 0, 2) . $contColuna }}</span>
    <span class="text-muted" style="font-size: 0.8rem!Important;">
        {{ nomeCampoTabNovoPacNormalizado($value['colunm_name']) }}
    </span> {!! $auditColumn !!}

</label>
