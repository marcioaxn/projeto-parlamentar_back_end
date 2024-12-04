@if (isset($nomeTema) && !empty($nomeTema))

    @if ($nomeTema === '1. Não alterar')
        <div class="text-bg-secondary m-0 mb-1 p-0" style="font-size: 0.2rem!Important;">&nbsp;</div>
    @elseif ($nomeTema === '2. Preenchimento Facultativo')
        <div class="text-bg-primary m-0 mb-1 p-0" style="font-size: 0.2rem!Important;">&nbsp;</div>
    @elseif ($nomeTema === '4. Orçamentário/Financeiro')
        <div class="text-bg-success m-0 mb-1 p-0" style="font-size: 0.2rem!Important;">&nbsp;</div>
    @else
        <div class="text-bg-warning m-0 mb-1 p-0" style="font-size: 0.2rem!Important;">&nbsp;</div>
    @endif

@endif
