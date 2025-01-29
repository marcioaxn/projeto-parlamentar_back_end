@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Agendas</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <style>
            
        </style>

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
                    if (!info.event.backgroundColor || info.event.backgroundColor === "") {
                        info.event.setProp('backgroundColor', '#FFFFFF');
                    }
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    const event = info.event;
                    const modalEdit = $('#editModal');

                    // Preencher todos os campos
                    modalEdit.find('#cod_agenda').val(event.id);
                    modalEdit.find('#dsc_titulo').val(event.title);
                    modalEdit.find('#dsc_descricao').val(event.extendedProps.description);
                    modalEdit.find('#dat_inicio').val(moment(event.start).format('YYYY-MM-DDTHH:mm'));
                    modalEdit.find('#dat_fim').val(moment(event.end).format('YYYY-MM-DDTHH:mm'));
                    modalEdit.find('#nom_cor').val(event.backgroundColor);
                    modalEdit.find('#dsc_url').val(event.url);
                    modalEdit.find('#cod_parlamentar').val(event.extendedProps.cod_parlamentar);

                    // Configurar recorrência se existir
                    if (event.extendedProps.ind_recorrente) {
                        modalEdit.find('#ind_recorrente').prop('checked', true);
                        modalEdit.find('#frequencia').prop('disabled', false);
                        modalEdit.find('#div_dat_fim_recorrencia').show();
                    }

                    modalEdit.modal('show');
                },
                dateClick: function(info) {
                    // Ajuste para usar a hora atual ao invés de 00:00
                    const clickedMoment = moment(info.date);
                    const currentHour = clickedMoment.format('HH:mm');

                    // Se for clique em dia inteiro (allDay), usar horário comercial (8:00)
                    if (info.allDay) {
                        clickedMoment.hour(8).minute(0);
                    }

                    const formattedDateTimeInput = clickedMoment.format('YYYY-MM-DDTHH:mm');
                    document.getElementById('dat_inicio').value = formattedDateTimeInput;

                    // Adiciona 1 hora para data fim
                    const endMoment = moment(clickedMoment).add(1, 'hour');
                    const formattedEndDateTimeInput = endMoment.format('YYYY-MM-DDTHH:mm');
                    document.getElementById('dat_fim').value = formattedEndDateTimeInput;

                    $('#createModal').modal('show');
                },
                eventTimeFormat: { // Formato da hora nos eventos
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false // Remove AM/PM
                },
                //Outras opções que você pode adicionar:
                headerToolbar: { // Define os botões do cabeçalho
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                eventDidMount: function(info) {
                    const event = info.event;
                    const description = event.extendedProps.description;
                    if (description) {
                        tippy(info.el, {
                            content: description,
                            placement: 'top',
                            arrow: true
                        });
                    }
                },
                navLinks: true, // Habilita a navegação clicando nos dias/semanas
                weekNumbers: true, // Mostra o número da semana
                // ... outras opções que você queira adicionar
            });
            calendar.render();
        });

        // script de recorrência
        document.addEventListener('DOMContentLoaded', function() {
            const setupRecorrencia = (formId) => {
                const form = document.getElementById(formId);
                if (!form) return;

                const ind_recorrente = form.querySelector('#ind_recorrente');
                const frequencia = form.querySelector('#frequencia');
                const div_dat_fim_recorrencia = form.querySelector('#div_dat_fim_recorrencia');

                // Configuração inicial
                frequencia.style.display = ind_recorrente.checked ? 'block' : 'none';
                div_dat_fim_recorrencia.style.display = ind_recorrente.checked ? 'block' : 'none';

                ind_recorrente.addEventListener('change', function() {
                    frequencia.style.display = this.checked ? 'block' : 'none';
                    div_dat_fim_recorrencia.style.display = this.checked ? 'block' : 'none';
                    frequencia.disabled = !this.checked;
                });
            };

            // Configurar para ambos os formulários
            setupRecorrencia('createForm');
            setupRecorrencia('editForm');
        });
    </script>
@endsection
