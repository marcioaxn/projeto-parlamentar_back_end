@section('content')
    <div class="container">
        <h1>Agendas</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div id='calendar'></div>

        @include('agendas.modals.create')
        @include('agendas.modals.edit')
        @include('agendas.modals.delete')
    </div>

    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'pt-br',
                initialView: 'dayGridMonth',
                events: "{{ route('eventos.getEvents') }}",
                selectable: true,
                eventDidMount: function(info) {
                    if (info.event.color) {
                        info.el.style.color = info.event.color;
                    }
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    const event = info.event;
                    const modalEdit = $('#editModal');

                    // Preenche os campos do modal de edição
                    modalEdit.find('#cod_agenda').val(event.id);
                    modalEdit.find('#dsc_titulo').val(event.title);
                    modalEdit.find('#dsc_descricao').val(event.extendedProps.description);
                    modalEdit.find('#dat_inicio').val(moment(event.start).format('YYYY-MM-DDTHH:mm'));
                    modalEdit.find('#dat_fim').val(moment(event.end).format('YYYY-MM-DDTHH:mm'));
                    modalEdit.find('#nom_cor').val(event.color);
                    modalEdit.find('#dsc_url').val(event.extendedProps.url);
                    modalEdit.find('#cod_parlamentar').val(event.extendedProps.cod_parlamentar);

                    // Configura recorrência
                    if (event.extendedProps.ind_recorrente) {
                        modalEdit.find('#ind_recorrente').prop('checked', true);
                        modalEdit.find('#frequencia').prop('disabled', false);
                        modalEdit.find('#div_dat_fim_recorrencia').show();
                    }

                    modalEdit.modal('show');
                },
                dateClick: function(info) {
                    const clickedMoment = moment(info.date);
                    const formattedDateTimeInput = clickedMoment.format('YYYY-MM-DDTHH:mm');
                    document.getElementById('dat_inicio').value = formattedDateTimeInput;

                    const endMoment = moment(clickedMoment).add(1, 'hour');
                    const formattedEndDateTimeInput = endMoment.format('YYYY-MM-DDTHH:mm');
                    document.getElementById('dat_fim').value = formattedEndDateTimeInput;

                    $('#createModal').modal('show');
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                navLinks: true,
                weekNumbers: true,
            });
            calendar.render();
        });

        // Função para enviar formulário via AJAX (genérica)
        function submitForm(url, method, formData, successCallback) {
            var xhr = new XMLHttpRequest();
            xhr.open(method, url, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            if (method === 'POST' || method === 'PUT') {
                xhr.setRequestHeader('X-HTTP-Method-Override', method === 'PUT' ? 'PUT' : 'POST');
            }

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        successCallback();
                    }
                } else {
                    alert('Erro: ' + xhr.responseText);
                }
            };

            xhr.send(formData);
        }
    </script>
@endsection
