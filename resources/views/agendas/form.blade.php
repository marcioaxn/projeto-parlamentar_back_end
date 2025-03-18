<div class="row">
    <div class="col-12 mb-3">
        <label for="dsc_titulo" class="form-label">Título *</label>
        <input type="text" class="form-control" id="dsc_titulo" name="dsc_titulo" required>
    </div>

    <div class="col-12 mb-3">
        <label for="dsc_descricao" class="form-label">Descrição</label>
        <textarea class="form-control" id="dsc_descricao" name="dsc_descricao" rows="3"></textarea>
    </div>

    <div class="col-md-6 mb-3">
        <label for="dat_inicio" class="form-label">Data Início *</label>
        <input type="datetime-local" class="form-control" id="dat_inicio" name="dat_inicio" required>
    </div>

    <div class="col-md-6 mb-3">
        <label for="dat_fim" class="form-label">Data Fim *</label>
        <input type="datetime-local" class="form-control" id="dat_fim" name="dat_fim" required>
    </div>

    <div class="col-md-6 mb-3">
        <label for="nom_cor" class="form-label">Cor do Evento</label>
        <input type="color" class="form-control form-control-color" id="nom_cor" name="nom_cor" value="#3788D8">
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="ind_recorrente" name="ind_recorrente">
            <label class="form-check-label" for="ind_recorrente">Evento Recorrente</label>
        </div>
    </div>

    <div class="col-12 mb-3" id="div_dat_fim_recorrencia" style="display: none;">
        <label for="dat_fim_recorrencia" class="form-label">Data Fim da Recorrência</label>
        <input type="date" class="form-control" id="dat_fim_recorrencia" name="dat_fim_recorrencia">
    </div>

    <div class="col-md-6 mb-3">
        <label for="dsc_url" class="form-label">URL Relacionada</label>
        <input type="url" class="form-control" id="dsc_url" name="dsc_url" placeholder="https://exemplo.com">
    </div>

    <div class="col-md-6 mb-3">
        <label for="frequencia" class="form-label">Frequência da Recorrência</label>
        <select name="frequencia" id="frequencia" class="form-select" disabled>
            <option value="DAILY">Diária</option>
            <option value="WEEKLY">Semanal</option>
            <option value="MONTHLY">Mensal</option>
            <option value="YEARLY">Anual</option>
        </select>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const indRecorrente = document.getElementById('ind_recorrente');
        const frequencia = document.getElementById('frequencia');
        const divFimRecorrencia = document.getElementById('div_dat_fim_recorrencia');

        if (indRecorrente) {
            indRecorrente.addEventListener('change', function() {
                const isChecked = this.checked;
                frequencia.disabled = !isChecked;
                divFimRecorrencia.style.display = isChecked ? 'block' : 'none';
            });
        }
    });
</script>
