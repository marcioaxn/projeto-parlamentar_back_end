<div class="mb-3">
    <label for="dsc_titulo" class="form-label">Título</label>
    <input type="text" class="form-control" id="dsc_titulo" name="dsc_titulo"
        value="{{ $agenda->dsc_titulo ?? old('dsc_titulo') }}" required>
</div>

<div class="mb-3">
    <label for="dsc_descricao" class="form-label">Descrição</label>
    <textarea class="form-control" id="dsc_descricao" name="dsc_descricao">{{ $agenda->dsc_descricao ?? old('dsc_descricao') }}</textarea>
</div>

<div class="mb-3">
    <label for="dat_inicio" class="form-label">Data Início</label>
    <input type="datetime-local" class="form-control" id="dat_inicio" name="dat_inicio"
        value="{{ isset($agenda->dat_inicio) ? $agenda->dat_inicio->format('Y-m-d\TH:i') : old('dat_inicio') }}"
        required>
</div>

<div class="mb-3">
    <label for="dat_fim" class="form-label">Data Fim</label>
    <input type="datetime-local" class="form-control" id="dat_fim" name="dat_fim"
        value="{{ isset($agenda->dat_fim) ? $agenda->dat_fim->format('Y-m-d\TH:i') : old('dat_fim') }}" required>
</div>

<div class="mb-3">
    <label for="nom_cor" class="form-label">Cor</label>
    <input type="color" class="form-control form-control-color" id="nom_cor" name="nom_cor"
        value="{{ $agenda->nom_cor ?? old('nom_cor') }}">
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="ind_recorrente" name="ind_recorrente"
        {{ isset($agenda) && $agenda->ind_recorrente ? 'checked' : (old('ind_recorrente') ? 'checked' : '') }}>
    <label class="form-check-label" for="ind_recorrente">Recorrente</label>
</div>

<div class="mb-3">
    <label for="des_url" class="form-label">URL</label>
    <input type="url" class="form-control" id="des_url" name="des_url"
        value="{{ $agenda->des_url ?? old('des_url') }}">
</div>
<div class="mb-3">
    <label for="cod_parlamentar" class="form-label">Parlamentar</label>
    <select name="cod_parlamentar" id="cod_parlamentar" class="form-select" required>
        <option value="">Selecione um Parlamentar</option>
        @foreach ($parlamentares as $parlamentar)
            <option value="{{ $parlamentar->cod_parlamentar }}"
                {{ (isset($agenda) && $agenda->cod_parlamentar == $parlamentar->cod_parlamentar) || old('cod_parlamentar') == $parlamentar->cod_parlamentar ? 'selected' : '' }}>
                {{ $parlamentar->nom_parlamentar }}
            </option>
        @endforeach
    </select>
</div>
