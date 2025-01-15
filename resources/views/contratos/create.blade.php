@extends('layouts.app')

@section('content')
    <style>
        /* Estilos para validação em tempo real */
        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }

        .is-invalid~.invalid-feedback {
            display: block;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .is-valid {
            border-color: #198754;
        }

        /* Estilos para o autocomplete do gabinete */
        .position-relative {
            position: relative;
        }

        /* Estilos para o preview do contrato */
        .preview-content {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .preview-content h4 {
            margin-bottom: 20px;
            color: #0d6efd;
        }

        .preview-content p {
            margin-bottom: 10px;
        }

        .preview-content strong {
            color: #495057;
        }
    </style>
    <div class="">
        <div class="row mb-4">
            <div class="col">
                <h1>Novo Contrato</h1>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contratos.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="cod_gabinete" class="form-label"><span class="text-bold font-numero">1.</span>
                        Gabinete</label>
                    <select name="cod_gabinete" id="cod_gabinete" class="form-select">
                        <option value="">Selecione um Gabinete</option>
                        @foreach ($gabinetes as $gabinete)
                            <option value="{{ $gabinete->cod_gabinete }}"
                                {{ old('cod_gabinete') == $gabinete->cod_gabinete ? 'selected' : '' }}>
                                {{ $gabinete->nom_gabinete }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="cod_plano" class="form-label"><span class="text-bold font-numero">2.</span> Plano</label>
                    <select name="cod_plano" id="cod_plano" class="form-select">
                        <option value="">Selecione um Plano</option>
                        @foreach ($planos as $plano)
                            <option value="{{ $plano->cod_plano }}"
                                {{ old('cod_plano') == $plano->cod_plano ? 'selected' : '' }}>
                                {{ $plano->nom_plano }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="dat_inicio" class="form-label"><span class="text-bold font-numero">3.</span> Data
                        Início</label>
                    <input type="date" name="dat_inicio" id="dat_inicio" class="form-control"
                        value="{{ old('dat_inicio') }}">
                </div>

                <div class="col-md-2">
                    <label for="dat_fim" class="form-label"><span class="text-bold font-numero">4.</span> Data Fim</label>
                    <input type="date" name="dat_fim" id="dat_fim" class="form-control" value="{{ old('dat_fim') }}">
                </div>

                <div class="col-md-3">
                    <label for="val_total" class="form-label"><span class="text-bold font-numero">5.</span> Valor
                        Total</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" name="val_total" id="val_total"
                            class="form-control text-right mascara-dinheiro font-numero"
                            value="{{ old('val_total') ?? '0,00' }}" step="0.01">
                    </div>
                </div>

                <div class="col-md-2">
                    <label for="val_desconto_aplicado" class="form-label"><span class="text-bold font-numero">6.</span>
                        Valor Desconto</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" name="val_desconto_aplicado" id="val_desconto_aplicado"
                            class="form-control text-right mascara-dinheiro font-numero"
                            value="{{ old('val_desconto_aplicado') ?? '0,00' }}" step="0.01" disabled>
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="val_sub_total" class="form-label"><span class="text-bold font-numero">7.</span> Valor
                        Subtotal</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" name="val_sub_total" id="val_sub_total"
                            class="form-control text-right mascara-dinheiro font-numero" value="0,00" readonly>
                    </div>
                </div>

                <div class="col-md-9">
                    <label for="dsc_observacoes" class="form-label"><span class="text-bold font-numero">8.</span>
                        Observações</label>
                    <textarea name="dsc_observacoes" id="dsc_observacoes" class="form-control" rows="3">{{ old('dsc_observacoes') }}</textarea>
                </div>

                <div class="col-md-3">
                    <label for="sta_ativo" class="form-label"><span class="text-bold font-numero">9.</span> Status</label>
                    <select name="sta_ativo" id="sta_ativo" class="form-select">
                        <option value="A" {{ old('sta_ativo') == 'A' ? 'selected' : '' }}>Ativo</option>
                        <option value="I" {{ old('sta_ativo') == 'I' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="{{ route('contratos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
        </form>
    </div>

    <script>
        // Mapeamento dos campos na ordem de navegação
        const fieldOrder = [
            'cod_gabinete',
            'cod_plano',
            'dat_inicio',
            'dat_fim',
            'val_total',
            'dsc_observacoes',
            'sta_ativo'
        ];

        // Função para configurar a navegação automática
        function setupAutoNavigation() {
            fieldOrder.forEach((fieldId, index) => {
                const currentField = document.getElementById(fieldId);
                if (!currentField) return;

                // Adiciona evento change para select elements
                if (currentField.tagName === 'SELECT') {
                    currentField.addEventListener('change', () => {
                        if (index < fieldOrder.length - 1) {
                            const nextField = document.getElementById(fieldOrder[index + 1]);
                            if (nextField && !nextField.disabled) {
                                nextField.focus();
                            }
                        }
                    });
                }

                // Adiciona evento para campos de data
                if (currentField.type === 'date') {
                    currentField.addEventListener('input', () => {
                        if (currentField.value && index < fieldOrder.length - 1) {
                            const nextField = document.getElementById(fieldOrder[index + 1]);
                            if (nextField && !nextField.disabled) {
                                nextField.focus();
                            }
                        }
                    });
                }
            });
        }

        // Função para validar datas
        function setupDateValidation() {
            const datInicio = document.getElementById('dat_inicio');
            const datFim = document.getElementById('dat_fim');

            datInicio.addEventListener('change', () => {
                datFim.min = datInicio.value;
                if (datFim.value && datFim.value < datInicio.value) {
                    datFim.value = datInicio.value;
                }
            });

            // Define a data mínima como hoje
            const today = new Date().toISOString().split('T')[0];
            datInicio.min = today;
            if (!datInicio.value) {
                datFim.disabled = true;
            }

            datInicio.addEventListener('input', () => {
                datFim.disabled = !datInicio.value;
                if (datInicio.value) {
                    datFim.min = datInicio.value;
                }
            });
        }

        // Configuração do campo de valor total
        function setupValTotal() {
            const valTotalInput = document.getElementById('val_total');
            const valDescontoInput = document.getElementById('val_desconto_aplicado');
            const valSubTotalInput = document.getElementById('val_sub_total');

            valTotalInput.addEventListener('input', function() {
                const valor = this.value.replace(/\D/g, '');
                const valorFormatado = (valor / 100).toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                this.value = valorFormatado;

                // Atualiza estado do campo de desconto
                valDescontoInput.disabled = !valor || valor === '0';
                if (!valor || valor === '0') {
                    valDescontoInput.value = '0,00';
                    valSubTotalInput.value = '0,00';
                }
                calcularSubTotal();
            });

            // Adiciona listener para o evento 'change' também
            valTotalInput.addEventListener('change', function() {
                valDescontoInput.disabled = !this.value || this.value === '0,00';
                if (this.value && this.value !== '0,00') {
                    calcularSubTotal();
                }
            });
        }

        // Mantém a função calcularSubTotal existente
        function calcularSubTotal() {
            const valTotalInput = document.getElementById('val_total');
            const valDescontoInput = document.getElementById('val_desconto_aplicado');
            const valSubTotalInput = document.getElementById('val_sub_total');

            let valTotal = parseFloat(valTotalInput.value.replace(/\./g, '').replace(',', '.'));
            let valDesconto = parseFloat(valDescontoInput.value.replace(/\./g, '').replace(',', '.'));

            if (isNaN(valTotal)) valTotal = 0;
            if (isNaN(valDesconto)) valDesconto = 0;

            let subTotal = valTotal - valDesconto;
            if (isNaN(subTotal)) subTotal = 0;

            valSubTotalInput.value = subTotal.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Inicialização
        window.addEventListener('load', function() {
            setupAutoNavigation();
            setupDateValidation();
            setupValTotal();

            const valDescontoInput = document.getElementById('val_desconto_aplicado');
            valDescontoInput.addEventListener('input', calcularSubTotal);
            valDescontoInput.disabled = true;

            calcularSubTotal();
        });

        // Função para validação em tempo real
        function setupRealTimeValidation() {
            const requiredFields = {
                'cod_gabinete': 'Gabinete é obrigatório',
                'cod_plano': 'Plano é obrigatório',
                'dat_inicio': 'Data de início é obrigatória',
                'dat_fim': 'Data de fim é obrigatória',
                'val_total': 'Valor total é obrigatório'
            };

            Object.keys(requiredFields).forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const feedbackDiv = document.createElement('div');
                feedbackDiv.className = 'invalid-feedback';
                feedbackDiv.textContent = requiredFields[fieldId];
                field.parentNode.appendChild(feedbackDiv);

                field.addEventListener('blur', () => {
                    if (!field.value) {
                        field.classList.add('is-invalid');
                        field.classList.remove('is-valid');
                    } else {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    }
                });

                field.addEventListener('input', () => {
                    if (field.value) {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    }
                });
            });
        }

        // Função para adicionar tooltips
        function setupTooltips() {
            const tooltips = {
                'cod_gabinete': 'Selecione o gabinete responsável pelo contrato',
                'cod_plano': 'Escolha o plano que será aplicado',
                'dat_inicio': 'Data de início do contrato',
                'dat_fim': 'Data de término do contrato',
                'val_total': 'Valor total do contrato',
                'val_desconto_aplicado': 'Valor do desconto a ser aplicado',
                'val_sub_total': 'Valor final após aplicação do desconto',
                'dsc_observacoes': 'Adicione observações relevantes sobre o contrato',
                'sta_ativo': 'Define se o contrato está ativo ou inativo'
            };

            Object.keys(tooltips).forEach(fieldId => {
                const field = document.getElementById(fieldId);
                field.setAttribute('data-bs-toggle', 'tooltip');
                field.setAttribute('data-bs-placement', 'top');
                field.setAttribute('title', tooltips[fieldId]);
            });

            // Inicializa os tooltips do Bootstrap
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Função para preview do contrato
        function setupContractPreview() {
            const form = document.querySelector('form');
            const previewButton = document.createElement('button');
            previewButton.type = 'button';
            previewButton.className = 'btn btn-info me-2';
            previewButton.textContent = 'Visualizar Contrato';

            form.querySelector('.btn-primary').before(previewButton);

            previewButton.addEventListener('click', () => {
                const previewModal = document.createElement('div');
                previewModal.className = 'modal fade';
                previewModal.id = 'previewModal';
                previewModal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Preview do Contrato</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="preview-content">
                            <h4>Detalhes do Contrato</h4>
                            <p><strong>Gabinete:</strong> ${document.querySelector('#cod_gabinete option:checked').text}</p>
                            <p><strong>Plano:</strong> ${document.querySelector('#cod_plano option:checked').text}</p>
                            <p><strong>Período:</strong> ${document.getElementById('dat_inicio').value} a ${document.getElementById('dat_fim').value}</p>
                            <p><strong>Valor Total:</strong> R$ ${document.getElementById('val_total').value}</p>
                            <p><strong>Desconto:</strong> R$ ${document.getElementById('val_desconto_aplicado').value}</p>
                            <p><strong>Valor Final:</strong> R$ ${document.getElementById('val_sub_total').value}</p>
                            <p><strong>Status:</strong> ${document.querySelector('#sta_ativo option:checked').text}</p>
                            <p><strong>Observações:</strong></p>
                            <p>${document.getElementById('dsc_observacoes').value || 'Nenhuma observação'}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        `;

                document.body.appendChild(previewModal);
                const modal = new bootstrap.Modal(previewModal);
                modal.show();

                previewModal.addEventListener('hidden.bs.modal', () => {
                    previewModal.remove();
                });
            });
        }

        // Função para confirmar cancelamento
        function setupCancelConfirmation() {
            const cancelButton = document.querySelector('a.btn-secondary');
            cancelButton.addEventListener('click', (e) => {
                const form = document.querySelector('form');
                const formData = new FormData(form);
                let hasData = false;

                for (const [key, value] of formData.entries()) {
                    if (value && !['sta_ativo'].includes(key)) {
                        hasData = true;
                        break;
                    }
                }

                if (hasData) {
                    e.preventDefault();
                    if (!confirm('Existem dados preenchidos no formulário. Deseja realmente cancelar?')) {
                        return;
                    }
                    window.location.href = cancelButton.href;
                }
            });
        }

        // Função para autocomplete do gabinete
        function setupGabineteAutocomplete() {
            const gabineteSelect = document.getElementById('cod_gabinete');
            const searchWrapper = document.createElement('div');
            searchWrapper.className = 'position-relative';

            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.className = 'form-control';
            searchInput.placeholder = 'Buscar gabinete...';

            gabineteSelect.parentNode.insertBefore(searchWrapper, gabineteSelect);
            searchWrapper.appendChild(searchInput);
            searchWrapper.appendChild(gabineteSelect);

            searchInput.addEventListener('input', (e) => {
                const searchText = e.target.value.toLowerCase();
                Array.from(gabineteSelect.options).forEach(option => {
                    const optionText = option.text.toLowerCase();
                    option.style.display = optionText.includes(searchText) ? '' : 'none';
                });
            });

            gabineteSelect.addEventListener('change', () => {
                if (gabineteSelect.value) {
                    searchInput.value = gabineteSelect.options[gabineteSelect.selectedIndex].text;
                } else {
                    searchInput.value = '';
                }
            });
        }

        // Inicialização de todas as novas funcionalidades
        window.addEventListener('load', function() {
            setupRealTimeValidation();
            setupTooltips();
            setupContractPreview();
            setupCancelConfirmation();
            setupGabineteAutocomplete();
        });

        // Função para formatar valor em Real
        function formatarReal(valor) {
            return valor.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Função para configurar o comportamento do select de planos
        function setupPlanoChange() {
            const planoSelect = document.getElementById('cod_plano');
            const valTotalInput = document.getElementById('val_total');
            const valDescontoInput = document.getElementById('val_desconto_aplicado');

            planoSelect.addEventListener('change', function() {
                const codPlano = this.value;

                if (!codPlano) {
                    valTotalInput.value = '0,00';
                    valDescontoInput.disabled = true;
                    const event = new Event('change');
                    valTotalInput.dispatchEvent(event);
                    return;
                }

                // Fazer requisição AJAX para buscar o valor do plano
                fetch(`{{ url('/contratos/plano-valor/${codPlano}') }}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.valor) {
                            valTotalInput.value = formatarReal(parseFloat(data.valor));
                            // Habilita o campo de desconto e dispara o evento de change
                            valDescontoInput.disabled = false;
                            const event = new Event('change');
                            valTotalInput.dispatchEvent(event);
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar valor do plano:', error);
                        valTotalInput.value = '0,00';
                        valDescontoInput.disabled = true;
                        const event = new Event('change');
                        valTotalInput.dispatchEvent(event);
                    });
            });
        }

        // Inicialização quando o documento estiver pronto
        document.addEventListener('DOMContentLoaded', function() {
            setupPlanoChange();
        });
    </script>
@endsection
