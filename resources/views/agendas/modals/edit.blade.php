<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true" aria-modal="true"
    role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="editForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Agenda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">

                    @csrf
                    @method('PUT')
                    <input type="hidden" id="cod_agenda" name="cod_agenda">
                    @include('agendas.form')

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var eventId = document.getElementById('cod_agenda').value;

        // URL completa com base no endereço do sistema
        window.baseUrl = "{{ url('/') }}";
        var url = `${window.baseUrl}/agendas/${eventId}`;

        // Adicione o método spoofing e o token CSRF
        formData.append('_method', 'PUT');
        formData.append('_token', '{{ csrf_token() }}'); // Se estiver em um arquivo .blade.php

        submitForm(
            url,
            'POST', // Envie como POST com _method=PUT
            formData,
            function() {
                $('#editModal').modal('hide');
                window.location.reload();
            }
        );
    });
</script>
