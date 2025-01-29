<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Agenda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('agendas.update', $agenda ?? '') }}">
                    @csrf @method('PUT')
                    <input type="hidden" id="cod_agenda" name="cod_agenda">
                    @include('agendas.form')
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    document.getElementById('dat_inicio').addEventListener('change', function() {
        // Obtém a data e hora de início usando Moment.js, *considerando o fuso horário local*
        const startMoment = moment(this.value);

        // Adiciona uma hora
        const endMoment = moment(startMoment).add(1, 'hour');

        // Formata a data e hora de fim para o formato esperado pelo input
        const formattedEndDate = endMoment.format('YYYY-MM-DDTHH:mm');

        document.getElementById('dat_fim').value = formattedEndDate;
    });
</script>
