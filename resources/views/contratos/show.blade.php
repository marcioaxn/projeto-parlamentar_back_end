@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detalhes do Contrato</h1>

        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Gabinete:</dt>
                    <dd class="col-sm-9">{{ $contrato->gabinete->nom_gabinete ?? 'Não informado' }}</dd>

                    <dt class="col-sm-3">Plano:</dt>
                    <dd class="col-sm-9">{{ $contrato->plano->nom_plano ?? 'Não informado' }}</dd>

                    <dt class="col-sm-3">Data de Início:</dt>
                    <dd class="col-sm-9">{{ $contrato->dat_inicio ? $contrato->dat_inicio->format('d/m/Y') : 'Não informada' }}</dd>

                    <dt class="col-sm-3">Data de Fim:</dt>
                    <dd class="col-sm-9">{{ $contrato->dat_fim ? $contrato->dat_fim->format('d/m/Y') : 'Não informada' }}</dd>

                    <dt class="col-sm-3">Valor Total:</dt>
                    <dd class="col-sm-9">R$ {{ $contrato->val_total ? number_format($contrato->val_total, 2, ',', '.') : '0,00' }}</dd>

                    <dt class="col-sm-3">Valor Desconto:</dt>
                    <dd class="col-sm-9">R$ {{ $contrato->val_desconto_aplicado ? number_format($contrato->val_desconto_aplicado, 2, ',', '.') : '0,00' }}</dd>

                    <dt class="col-sm-3">Valor Subtotal:</dt>
                    <dd class="col-sm-9">R$ {{ $contrato->val_sub_total ? number_format($contrato->val_sub_total, 2, ',', '.') : '0,00' }}</dd>

                    <dt class="col-sm-3">Observações:</dt>
                    <dd class="col-sm-9">{{ $contrato->dsc_observacoes ?? 'Nenhuma observação' }}</dd>

                    <dt class="col-sm-3">Status:</dt>
                    <dd class="col-sm-9">{{ $contrato->sta_ativo == 'A' ? 'Ativo' : 'Inativo' }}</dd>
                </dl>

                <div class="mt-4">
                    <a href="{{ route('contratos.edit', $contrato) }}" class="btn btn-primary">Editar</a>
                    <a href="{{ route('contratos.index') }}" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
        </div>
    </div>
@endsection
