@extends('layouts.app')

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'pt-br',
                initialView: 'dayGridMonth',
                events: "{{ route('agendas.getEvents') }}",
                eventClick: function(info) {
                    // Preenche o modal de edição com os dados do evento
                    $('#editModal #cod_agenda').val(info.event.id);
                    $('#editModal #dsc_titulo').val(info.event.title);
                    $('#editModal #dat_inicio').val(info.event.startStr);
                    $('#editModal #dat_fim').val(info.event.endStr);
                    $('#editModal #nom_cor').val(info.event.backgroundColor);
                    $('#editModal #des_url').val(info.event.url);

                    // Abre o modal de edição
                    $('#editModal').modal('show');
                },
                dateClick: function(info) {
                    $('#createModal #dat_inicio').val(info.dateStr);
                    $('#createModal #dat_fim').val(info.dateStr);
                    $('#createModal').modal('show');
                },
                eventTimeFormat: { // like '14:30:00'
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                }
            });
            calendar.render();
        });
    </script>
@endsection
