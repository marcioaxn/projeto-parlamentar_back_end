<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Agenda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('agendas.update', $agenda ?? '') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="cod_agenda" id="cod_agenda">
                    @include('agendas.form')
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>