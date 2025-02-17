<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Excluir Evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este evento?</p>
                <input type="hidden" id="cod_agenda">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Excluir</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('confirmDelete').addEventListener('click', function() {
        var eventId = document.getElementById('cod_agenda').value;
        var xhr = new XMLHttpRequest();
        xhr.open('DELETE', '/agendas/' + eventId, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute(
            'content'));

        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    $('#deleteModal').modal('hide');
                    window.location.reload();
                }
            } else {
                alert('Erro: ' + xhr.responseText);
            }
        };

        xhr.send();
    });
</script>
