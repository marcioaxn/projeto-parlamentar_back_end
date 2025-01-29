<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
        <label for="dsc_titulo" class="form-label">Título</label>
        <input type="text" class="form-control" id="dsc_titulo" name="dsc_titulo" required>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
        <label for="dsc_descricao" class="form-label">Descrição</label>
        <textarea class="form-control" id="dsc_descricao" name="dsc_descricao"></textarea>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
        <label for="dat_inicio" class="form-label">Data Início</label>
        <input type="datetime-local" class="form-control" id="dat_inicio" name="dat_inicio" required>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
        <label for="dat_fim" class="form-label">Data Fim</label>
        <input type="datetime-local" class="form-control" id="dat_fim" name="dat_fim" required>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
        <label for="nom_cor" class="form-label">Cor</label>
        <input type="color" class="form-control form-control-color" id="nom_cor" name="nom_cor">
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="ind_recorrente" name="ind_recorrente">
            <label class="form-check-label" for="ind_recorrente">Recorrente</label>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3" id="div_dat_fim_recorrencia"
        style="display: none;">
        <label for="dat_fim_recorrencia" class="form-label">Data Fim Recorrência</label>
        <input type="date" class="form-control" id="dat_fim_recorrencia" name="dat_fim_recorrencia">
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
        <label for="dsc_url" class="form-label">URL</label>
        <input type="url" class="form-control" id="dsc_url" name="dsc_url">
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
        <label for="cod_parlamentar" class="form-label">Parlamentar</label>
        <select name="cod_parlamentar" id="cod_parlamentar" class="form-select" required>
            <option value="">Selecione um Parlamentar</option>
            @foreach ($parlamentares as $parlamentar)
                <option value="{{ $parlamentar->cod_parlamentar }}">
                    {{ $parlamentar->nom_parlamentar }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
        <label for="frequencia" class="form-label">Frequência</label>
        <select name="frequencia" id="frequencia" class="form-select"
            {{ !isset($agenda) || !$agenda->ind_recorrente ? 'disabled' : '' }}>
            <option value="DAILY"
                {{ (isset($agenda) && $agenda->dsc_rrule && strpos($agenda->dsc_rrule, 'FREQ=DAILY') !== false) || old('frequencia') == 'DAILY' ? 'selected' : '' }}>
                Diariamente</option>
            <option value="WEEKLY"
                {{ (isset($agenda) && $agenda->dsc_rrule && strpos($agenda->dsc_rrule, 'FREQ=WEEKLY') !== false) || old('frequencia') == 'WEEKLY' ? 'selected' : '' }}>
                Semanalmente</option>
            <option value="MONTHLY"
                {{ (isset($agenda) && $agenda->dsc_rrule && strpos($agenda->dsc_rrule, 'FREQ=MONTHLY') !== false) || old('frequencia') == 'MONTHLY' ? 'selected' : '' }}>
                Mensalmente</option>
            <option value="YEARLY"
                {{ (isset($agenda) && $agenda->dsc_rrule && strpos($agenda->dsc_rrule, 'FREQ=YEARLY') !== false) || old('frequencia') == 'YEARLY' ? 'selected' : '' }}>
                Anualmente</option>
        </select>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ind_recorrente = document.getElementById('ind_recorrente');
        const frequencia = document.getElementById('frequencia');
        const div_dat_fim_recorrencia = document.getElementById('div_dat_fim_recorrencia');

        if (ind_recorrente) {
            ind_recorrente.addEventListener('change', function() {
                frequencia.disabled = !this.checked;
                div_dat_fim_recorrencia.style.display = this.checked ? 'block' : 'none';
            });
        }
    });
</script>
