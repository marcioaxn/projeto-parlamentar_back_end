<!-- CSS do FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
<!-- Toast CSS -->
<link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">

<style>
    #calendarioAgenda {
        background-color: #FFFFFF !important;
    }

    .fc-event {
        background-color: inherit;
    }
</style>

<div class="card">
    <div class="card-body" style="background-color: #FFFFFF!Important;">
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary" id="btnNovaAgendaModulo">Nova Agenda</button>
        </div>
        <div id='calendarioAgenda'></div>
    </div>
</div>

<!-- Modal para Cadastro/Edição de Agenda -->
<div class="modal fade" id="agendaModalModulo" tabindex="-1" aria-labelledby="agendaModalModuloLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agendaModalModuloLabel">Nova Agenda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="agendaFormModulo">
                    <input type="hidden" id="cod_agenda_modulo" name="cod_agenda">

                    <div class="mb-3">
                        <label for="dsc_titulo_modulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="dsc_titulo_modulo" name="dsc_titulo" required>
                        <div class="invalid-feedback">Título é obrigatório</div>
                    </div>

                    <div class="mb-3">
                        <label for="dat_inicio_modulo" class="form-label">Data de Início</label>
                        <input type="datetime-local" class="form-control" id="dat_inicio_modulo" name="dat_inicio" required>
                        <div class="invalid-feedback">Data de início é obrigatória</div>
                    </div>

                    <div class="mb-3">
                        <label for="dat_fim_modulo" class="form-label">Data de Fim</label>
                        <input type="datetime-local" class="form-control" id="dat_fim_modulo" name="dat_fim" required>
                        <div class="invalid-feedback">Data de fim é obrigatória</div>
                    </div>

                    <div class="mb-3">
                        <label for="dsc_descricao_modulo" class="form-label">Descrição</label>
                        <textarea class="form-control" id="dsc_descricao_modulo" name="dsc_descricao" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="dsc_local_modulo" class="form-label">Local</label>
                        <input type="text" class="form-control" id="dsc_local_modulo" name="dsc_local">
                    </div>

                    <div class="mb-3">
                        <label for="nom_cor_modulo" class="form-label">Cor</label>
                        <input type="color" class="form-control" id="nom_cor_modulo" name="nom_cor" value="#3788d8">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="ind_recorrente_modulo" name="ind_recorrente">
                        <label class="form-check-label" for="ind_recorrente_modulo">Evento Recorrente</label>
                    </div>

                    <div class="mb-3">
                        <label for="dsc_url_modulo" class="form-label">URL</label>
                        <input type="url" class="form-control" id="dsc_url_modulo" name="dsc_url">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnExcluirModulo" style="display:none;">Excluir</button>
                <button type="button" class="btn btn-primary" id="btnSalvarModulo">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Script isolado para o módulo de Agenda -->
<script type="text/javascript">
(function() {
    // Namespace isolado para o módulo de Agenda
    var ModuloAgenda = {
        calendar: null,
        agendaModal: null,
        
        // Inicialização do módulo
        init: function() {
            // Carrega as dependências necessárias
            this.carregarDependencias().then(() => {
                // Inicializa os elementos da interface
                this.initElements();
                
                // Verifica se a tab de agenda está ativa inicialmente
                var agendaTab = document.getElementById('tab<?php echo md5('Agenda/Audiências/Eventos'); ?>');
                
                if (agendaTab && agendaTab.classList.contains('active')) {
                    // Se a tab da agenda estiver ativa, inicializa o calendário
                    this.initCalendar();
                }
                
                // Adiciona listeners para todos os tabs
                var tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
                for (var i = 0; i < tabEls.length; i++) {
                    tabEls[i].addEventListener('shown.bs.tab', (event) => {
                        // Verifica se o tab ativado é o da agenda
                        if (event.target.id === 'tab<?php echo md5('Agenda/Audiências/Eventos'); ?>-tab') {
                            // Pequeno atraso para garantir que o DOM esteja pronto
                            setTimeout(() => {
                                this.initCalendar();
                            }, 100);
                        }
                    });
                }
            }).catch(error => {
                console.error('Erro ao inicializar o módulo de Agenda:', error);
            });
        },
        
        // Carrega as dependências necessárias
        carregarDependencias: function() {
            return new Promise((resolve, reject) => {
                // Verifica se jQuery já está disponível
                if (typeof jQuery === 'undefined') {
                    const jqueryScript = document.createElement('script');
                    jqueryScript.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
                    jqueryScript.onload = checkDependencies;
                    jqueryScript.onerror = () => reject('Falha ao carregar jQuery');
                    document.head.appendChild(jqueryScript);
                } else {
                    checkDependencies();
                }
                
                function loadScript(url, callback) {
                    const script = document.createElement('script');
                    script.src = url;
                    script.onload = callback;
                    script.onerror = () => reject(`Falha ao carregar ${url}`);
                    document.head.appendChild(script);
                }
                
                function loadCss(url) {
                    if (document.querySelector(`link[href="${url}"]`)) return;
                    
                    const link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = url;
                    document.head.appendChild(link);
                }
                
                function checkDependencies() {
                    // Verifica se FullCalendar já está disponível
                    if (typeof FullCalendar === 'undefined') {
                        loadScript('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js', () => {
                            loadScript('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales-all.min.js', checkToastr);
                        });
                    } else {
                        checkToastr();
                    }
                }
                
                function checkToastr() {
                    // Verifica se Toastr já está disponível
                    if (typeof toastr === 'undefined') {
                        loadCss('https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css');
                        loadScript('https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js', () => {
                            resolve();
                        });
                    } else {
                        resolve();
                    }
                }
            });
        },
        
        // Inicialização dos elementos da interface
        initElements: function() {
            // Inicializar o modal com Bootstrap
            this.agendaModal = new bootstrap.Modal(document.getElementById('agendaModalModulo'));
            
            // Evento de clique para novo evento
            document.getElementById('btnNovaAgendaModulo').addEventListener('click', this.abrirModalNovaAgenda.bind(this));
            
            // Evento de clique para salvar
            document.getElementById('btnSalvarModulo').addEventListener('click', this.salvarAgenda.bind(this));
            
            // Evento de clique para excluir
            document.getElementById('btnExcluirModulo').addEventListener('click', this.excluirAgenda.bind(this));
            
            // Validação do formulário
            const agendaForm = document.getElementById('agendaFormModulo');
            agendaForm.addEventListener('submit', (event) => {
                event.preventDefault();
                event.stopPropagation();
                if (!agendaForm.checkValidity()) {
                    agendaForm.classList.add('was-validated');
                    return;
                }
                this.salvarAgenda();
            });
            
            // Validação de data fim maior que data início
            document.getElementById('dat_inicio_modulo').addEventListener('change', this.validarDatas.bind(this));
            document.getElementById('dat_fim_modulo').addEventListener('change', this.validarDatas.bind(this));
            
            // Configuração do Toastr
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 3000
            };
        },
        
        // Validar datas para garantir que data fim seja posterior à data início
        validarDatas: function() {
            const dataInicio = document.getElementById('dat_inicio_modulo').value;
            const dataFim = document.getElementById('dat_fim_modulo').value;
            
            if (dataInicio && dataFim && new Date(dataInicio) > new Date(dataFim)) {
                document.getElementById('dat_fim_modulo').setCustomValidity('A data de fim deve ser posterior à data de início');
            } else {
                document.getElementById('dat_fim_modulo').setCustomValidity('');
            }
        },
        
        // Função de inicialização do calendário
        initCalendar: function() {
            var calendarEl = document.getElementById('calendarioAgenda');
            
            // Verificar se o elemento existe e está visível
            if (!calendarEl || calendarEl.offsetParent === null) {
                console.warn('Elemento do calendário não encontrado ou não visível');
                return; // Não inicializa se estiver oculto
            }
            
            // Destruir o calendário anterior se já existir
            if (this.calendar) {
                this.calendar.destroy();
            }
            
            // Criação do calendário
            this.calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                buttonText: {
                    today: 'Hoje',
                    month: 'Mês',
                    week: 'Semana',
                    day: 'Dia',
                    list: 'Lista'
                },
                editable: true,
                eventSources: [{
                    url: '{{ route('agenda.listar') }}',
                    method: 'GET',
                    failure: function() {
                        toastr.error('Erro ao carregar eventos');
                    }
                }],
                eventClick: (info) => {
                    this.abrirModalEdicao(info.event);
                },
                dateClick: (info) => {
                    this.abrirModalNovaAgendaPorData(info.date);
                },
                height: 'auto'
            });
            
            this.calendar.render();
            console.log('Calendário inicializado com sucesso');
        },
        
        // Abrir modal para nova agenda com data específica
        abrirModalNovaAgendaPorData: function(data) {
            // Resetar o formulário
            document.getElementById('agendaFormModulo').reset();
            document.getElementById('agendaFormModulo').classList.remove('was-validated');
            document.getElementById('cod_agenda_modulo').value = '';
            
            // Configurar o modal para inclusão
            document.getElementById('agendaModalModuloLabel').textContent = 'Nova Agenda';
            document.getElementById('btnExcluirModulo').style.display = 'none';
            
            // Criar data de hoje com a hora atual
            const agora = new Date();
            
            // Ajustar a data selecionada com a hora atual
            data.setHours(agora.getHours());
            data.setMinutes(agora.getMinutes());
            
            // Configurar data de início com a data clicada
            document.getElementById('dat_inicio_modulo').value = this.formatDateForInput(data);
            
            // Data de fim uma hora depois
            const horaDepois = new Date(data.getTime() + 60 * 60 * 1000);
            document.getElementById('dat_fim_modulo').value = this.formatDateForInput(horaDepois);
            
            // Abrir o modal
            this.agendaModal.show();
        },
        
        // Abrir modal para nova agenda
        abrirModalNovaAgenda: function() {
            // Resetar o formulário
            document.getElementById('agendaFormModulo').reset();
            document.getElementById('agendaFormModulo').classList.remove('was-validated');
            document.getElementById('cod_agenda_modulo').value = '';
            
            // Configurar o modal para inclusão
            document.getElementById('agendaModalModuloLabel').textContent = 'Nova Agenda';
            document.getElementById('btnExcluirModulo').style.display = 'none';
            
            // Configurar data de início e fim para o momento atual
            const agora = new Date();
            document.getElementById('dat_inicio_modulo').value = this.formatDateForInput(agora);
            
            // Data de fim uma hora depois
            const horaDepois = new Date(agora.getTime() + 60 * 60 * 1000);
            document.getElementById('dat_fim_modulo').value = this.formatDateForInput(horaDepois);
            
            // Abrir o modal
            this.agendaModal.show();
        },
        
        // Abrir modal para edição de agenda
        abrirModalEdicao: function(evento) {
            // Resetar o formulário
            document.getElementById('agendaFormModulo').reset();
            document.getElementById('agendaFormModulo').classList.remove('was-validated');
            
            // Configurar o modal para edição
            document.getElementById('agendaModalModuloLabel').textContent = 'Editar Agenda';
            document.getElementById('btnExcluirModulo').style.display = 'block';
            
            // Preencher o formulário com os dados do evento
            document.getElementById('cod_agenda_modulo').value = evento.id;
            document.getElementById('dsc_titulo_modulo').value = evento.title;
            
            // Formatação das datas para o formato do input datetime-local com ajuste do fuso horário
            const dataInicio = new Date(evento.start);
            const formattedStartDate = this.formatDateForInput(dataInicio);
            document.getElementById('dat_inicio_modulo').value = formattedStartDate;
            
            const dataFim = evento.end ? new Date(evento.end) : new Date(dataInicio.getTime() + 60 * 60 * 1000);
            const formattedEndDate = this.formatDateForInput(dataFim);
            document.getElementById('dat_fim_modulo').value = formattedEndDate;
            
            // Preencher os campos adicionais se existirem
            if (evento.extendedProps) {
                document.getElementById('dsc_descricao_modulo').value = evento.extendedProps.descricao || '';
                document.getElementById('dsc_local_modulo').value = evento.extendedProps.local || '';
                document.getElementById('dsc_url_modulo').value = evento.extendedProps.url || '';
                document.getElementById('ind_recorrente_modulo').checked = evento.extendedProps.recorrente || false;
            }
            
            // Cor do evento
            document.getElementById('nom_cor_modulo').value = evento.backgroundColor || '#3788d8';
            
            // Abrir o modal
            this.agendaModal.show();
        },
        
        // Função auxiliar para formatar data considerando o fuso horário local
        formatDateForInput: function(date) {
            const offset = date.getTimezoneOffset();
            const adjustedDate = new Date(date.getTime() - (offset * 60 * 1000));
            return adjustedDate.toISOString().slice(0, 16);
        },
        
        // Salvar agenda (inclusão ou edição)
        salvarAgenda: function() {
            console.log('Tentando salvar agenda...');
            
            // Validação do formulário do lado do cliente
            const form = document.getElementById('agendaFormModulo');
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                console.log('Formulário inválido');
                return;
            }
            
            // Validação adicional de datas
            const dataInicio = new Date(document.getElementById('dat_inicio_modulo').value);
            const dataFim = new Date(document.getElementById('dat_fim_modulo').value);
            
            if (dataInicio > dataFim) {
                toastr.error('A data de fim deve ser posterior à data de início');
                console.log('Data de fim menor que data de início');
                return;
            }
            
            // Coletar dados do formulário
            const formData = {
                cod_agenda: document.getElementById('cod_agenda_modulo').value,
                dsc_titulo: document.getElementById('dsc_titulo_modulo').value,
                dat_inicio: document.getElementById('dat_inicio_modulo').value,
                dat_fim: document.getElementById('dat_fim_modulo').value,
                dsc_descricao: document.getElementById('dsc_descricao_modulo').value,
                dsc_local: document.getElementById('dsc_local_modulo').value,
                nom_cor: document.getElementById('nom_cor_modulo').value,
                ind_recorrente: document.getElementById('ind_recorrente_modulo').checked ? 1 : 0,
                dsc_url: document.getElementById('dsc_url_modulo').value,
                _token: '{{ csrf_token() }}'
            };
            
            console.log('Dados do formulário:', formData);
            
            // Definir a URL e método com base se é inclusão ou edição
            const isEdicao = formData.cod_agenda !== '';
            const url = isEdicao ?
                '{{ route('agenda.atualizar') }}' :
                '{{ route('agenda.salvar') }}';
            const method = isEdicao ? 'PUT' : 'POST';
            
            console.log('Enviando requisição para:', url, 'com método:', method);
            
            // Requisição AJAX
            $.ajax({
                url: url,
                method: method,
                data: formData,
                dataType: 'json',
                success: (response) => {
                    console.log('Resposta do servidor:', response);
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        this.calendar.refetchEvents();
                        this.agendaModal.hide();
                    } else {
                        toastr.error(response.message || 'Erro ao salvar a agenda');
                    }
                },
                error: (xhr) => {
                    console.error('Erro na requisição:', xhr);
                    if (xhr.status === 422) {
                        // Erros de validação
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            toastr.error(field + ': ' + errors[field][0]);
                        }
                    } else {
                        toastr.error('Erro ao processar a requisição: ' + xhr.status + ' ' + xhr.statusText);
                    }
                }
            });
        },
        
        // Excluir agenda
        excluirAgenda: function() {
            const cod_agenda = document.getElementById('cod_agenda_modulo').value;
            
            if (!cod_agenda) {
                toastr.error('ID da agenda não encontrado');
                return;
            }
            
            if (!confirm('Tem certeza que deseja excluir esta agenda?')) {
                return;
            }
            
            $.ajax({
                url: '{{ route('agenda.excluir') }}',
                method: 'DELETE',
                data: {
                    cod_agenda: cod_agenda,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: (response) => {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        this.calendar.refetchEvents();
                        this.agendaModal.hide();
                    } else {
                        toastr.error(response.message || 'Erro ao excluir a agenda');
                    }
                },
                error: (xhr) => {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            toastr.error(field + ': ' + errors[field][0]);
                        }
                    } else {
                        toastr.error('Erro ao processar a requisição');
                    }
                }
            });
        }
    };
    
    // Inicializar o módulo quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa o módulo de agenda
        ModuloAgenda.init();
    });
})();
</script>