@extends('layouts.app')

@section('content')
    <!-- Início breadcrumbs -->
    <div id="portal-breadcrumbs-wrapper" class="m-0 pl-0 mb-3 d-print-none">
        <nav id="breadcrumbs" aria-label="Histórico de navegação (Breadcrumbs)">
            <div class="content">
                <span class="sr-only">Você está aqui:</span>
                <span class="home">
                    <a href="{!! url('/') !!}">
                        <span class="fas fa-home" aria-hidden="true"></span>
                        <span class="sr-only">Página Inicial</span>
                    </a>
                </span>

                <span class="pl-1 pr-1">></span>

                <span dir="ltr" id="breadcrumbs-2">
                    <a href="{!! url('novo-pac') !!}">
                        <span id="breadcrumbs-current">Novo PAC</span>
                    </a>
                </span>

                <span class="pl-1 pr-1">></span>

                <span dir="ltr" id="breadcrumbs-2">
                    <span id="breadcrumbs-current">Editar</span>
                </span>

            </div>
        </nav>
    </div>
    <!-- Fim breadcrumbs -->

    {!! Form::open([
        'method' => 'put',
        'url' => route('novo-pac.update', [$codPac]),
    ]) !!}

    @php
        // Obtenha a URL atual
        $currentUrl = $_SERVER['REQUEST_URI'];

        // Parse a URL para obter o caminho
        $parsedUrl = parse_url($currentUrl);

        // Obtenha o pathname da URL
        $path = $parsedUrl['path'];

        // Divida o pathname em segmentos usando o separador '/'
        $segments = explode('/', rtrim($path, '/'));

        // Pegue a última parte não vazia do pathname
        $lastSegment = end($segments);
    @endphp

    <div class="row">

        <div class="col-12 mt-1 mb-3 pt-0">

            <div class="rounded bg-info-sub-titulo-modal shadow-sm pt-2 pb-2 pl-1">
                <span class="pl-1" style="font-size: 1.1rem!Important;">{{ $nomEmpreendimento }}</span> <i
                    class="fas fa-eye pointer text-danger <?php $novoPac->auditoria->count() > 0 ? print 'visible' : print 'invisible'; ?>" data-bs-toggle="modal"
                    data-bs-target="#modalLog{{ 'DetalhePac' }}"></i> </label>
                {!! app(App\Http\Controllers\TabNovoPacController::class)->modalTabelaLog(
                    'DetalhePac',
                    $novoPac->auditoria->count() . ' ação(ões) realizada(s)',
                    $novoPac->auditoria,
                ) !!}
            </div>

        </div>

    </div>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">

            @php
                $contTema = 1;
            @endphp

            {{-- Início da montagem das tabs --}}
            @foreach ($getGrupoTemas as $tema)
                @php
                    $nomeTema = $tema->dsc_tema;
                    $nomeTemaParaId = tirarEspacosEntrePalavrasEPassarParaMinusculo($tema->dsc_tema);
                    $nomeDiv = 'div' . $contTema;
                @endphp

                <button class="nav-link @if ($nomeDiv === $lastSegment) {{ 'active' }} @endif font-numero"
                    id="{{ $nomeDiv }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $nomeDiv }}" type="button"
                    role="tab" aria-controls="{{ $nomeDiv }}" aria-selected="true"
                    onclick="javascript: fncAbaSelecionada('{{ $nomeDiv }}')">{{ $nomeTema }}</button>

                @php
                    $contTema++;
                @endphp
            @endforeach
            {{-- Fim da montagem das tabs --}}
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">

        @php
            $contTema = 1;
        @endphp

        {{-- Início da montagem das tabs-content --}}
        {{-- Início foreach do grupo de temas --}}
        @foreach ($getGrupoTemas as $tema)
            @php
                $nomeTema = $tema->dsc_tema;
                $nomeTemaParaId = tirarEspacosEntrePalavrasEPassarParaMinusculo($tema->dsc_tema);
                $nomeDiv = 'div' . $contTema;
            @endphp

            @if (isset($nomeTema) && !empty($nomeTema))
                @if ($nomeTema === '1. Não alterar')
                    @php
                        $bgNumeroLabel = 'bg-secondary';
                    @endphp
                @elseif ($nomeTema === '2. Preenchimento Facultativo')
                    @php
                        $bgNumeroLabel = 'bg-primary';
                    @endphp
                @elseif ($nomeTema === '4. Orçamentário/Financeiro')
                    @php
                        $bgNumeroLabel = 'bg-sucess';
                    @endphp
                @else
                    @php
                        $bgNumeroLabel = 'bg-warning text-dark';
                    @endphp
                @endif
            @endif

            <div class="tab-pane fade @if ($nomeDiv === $lastSegment) {{ 'show active' }} @endif" id="{{ $nomeDiv }}"
                role="tabpanel" aria-labelledby="{{ $nomeDiv }}-tab">

                <div class="row">

                    <div class="col-12 pt-0">
                        @include('pac.faixa-sinaliacao')
                    </div>

                    <div class="col-12 pt-1 pb-3">

                        <div class="row">

                            @php
                                $contColuna = 1;
                            @endphp
                            {{-- Início foreach da matriz contendo o resultado --}}
                            @foreach ($result as $key => $form)
                                @foreach ($form as $value)
                                    @if ($tema->dsc_tema === $key)
                                        @if ($value['colunm_name'] === 'cod_acao_orcamentaria' || $value['colunm_name'] === 'txt_resultado')
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-xl-3 col-xxl-2 mb-4">
                                            @elseif ($value['colunm_name'] === 'txt_restricao' || $value['colunm_name'] === 'txt_providencia')
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 col-xxl-2 mb-4">
                                                @else
                                                    <div
                                                        class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 col-xxl-2 mb-4">
                                        @endif

                                        @include('pac.auditoria.por-coluna')

                                        @if ($value['data_type'] === 'character varying')
                                            @if ($key != '1. Não alterar')
                                                @if (in_array($value['colunm_name'], $columnsBlnSimNao))
                                                    {!! Form::select($value['colunm_name'], ['Sim' => 'Sim', 'Não' => 'Não'], $value['value'], [
                                                        'class' => 'form-control text-dark',
                                                        'style' => 'cursor: pointer; width: 100% !Important;',
                                                        'id' => $value['colunm_name'],
                                                        'autocomplete' => 'off',
                                                        'placeholder' => 'Selecione',
                                                    ]) !!}
                                                @else
                                                    @if (array_key_exists($value['colunm_name'], $colunasComDominioProprio))
                                                        {!! Form::select(
                                                            $value['colunm_name'] . '[]',
                                                            ${$colunasComDominioProprio[$value['colunm_name']]},
                                                            explode(',', $value['value']),
                                                            [
                                                                'class' => 'form-control text-dark',
                                                                'multiple' => true,
                                                                'style' => 'cursor: pointer; width: 100% !Important;',
                                                                'id' => $value['colunm_name'],
                                                                'autocomplete' => 'off',
                                                            ],
                                                        ) !!}

                                                        <script type="text/javascript">
                                                            $('#{{ $value['colunm_name'] }}').select2();
                                                        </script>
                                                    @else
                                                        {!! Form::text($value['colunm_name'], $value['value'], [
                                                            'class' => 'form-control text-dark',
                                                            'id' => $value['colunm_name'],
                                                            'autocomplete' => 'off',
                                                        ]) !!}
                                                    @endif
                                                @endif
                                            @else
                                                <p class="bg-light rounded p-1">{{ $value['value'] }}</p>
                                            @endif
                                        @endif

                                        @if ($value['data_type'] === 'text')
                                            @if ($key != '1. Não alterar')
                                                {!! Form::textarea($value['colunm_name'], $value['value'], [
                                                    'class' => 'form-control text-dark',
                                                    'id' => $value['colunm_name'],
                                                    'rows' => 2,
                                                    'cols' => 50,
                                                ]) !!}
                                            @else
                                                <p class="bg-light rounded p-1">{{ $value['value'] }}</p>
                                            @endif
                                        @endif

                                        @if ($value['data_type'] === 'date')
                                            @if ($key != '1. Não alterar')
                                                {!! Form::date($value['colunm_name'], $value['value'], [
                                                    'class' => 'form-control text-dark text-right font-numero date',
                                                    'id' => $value['colunm_name'],
                                                    'style' => 'cursor: pointer',
                                                    'autocomplete' => 'off',
                                                    'required' => 'required',
                                                ]) !!}
                                            @else
                                                <p class="bg-light rounded p-1">
                                                    {{ converterData('EN', 'PTBR', $value['value']) }}</p>
                                            @endif
                                        @endif

                                        @if ($value['data_type'] === 'double precision' || $value['data_type'] === 'numeric' || $value['data_type'] === 'money')
                                            @if ($key != '1. Não alterar')
                                                @if (isset($value['value']))
                                                    @if ($value['colunm_name'] != 'prc_execucao_fisica')
                                                        {!! Form::text($value['colunm_name'], converteValor('MYSQL', 'PTBR', $value['value']), [
                                                            'class' => 'form-control text-dark text-right mascara-dinheiro font-numero',
                                                            'id' => $value['colunm_name'],
                                                            'autocomplete' => 'off',
                                                        ]) !!}
                                                    @else
                                                        {!! Form::text($value['colunm_name'], converteValor('MYSQL', 'PTBR', $value['value']), [
                                                            'class' => 'form-control text-dark text-right mascara-dinheiro font-numero',
                                                            'id' => $value['colunm_name'],
                                                            'onkeydown' => "javascript: limitInputToMax100('{$value['colunm_name']}');",
                                                            'autocomplete' => 'off',
                                                        ]) !!}
                                                    @endif
                                                @else
                                                    {!! Form::text($value['colunm_name'], null, [
                                                        'class' => 'form-control text-dark text-right mascara-dinheiro font-numero',
                                                        'id' => $value['colunm_name'],
                                                        'autocomplete' => 'off',
                                                    ]) !!}
                                                @endif
                                            @else
                                                <p class="text-right font-numero bg-light rounded p-1">
                                                    {{ converteValor('MYSQL', 'PTBR', $value['value']) }}</p>
                                            @endif
                                        @endif
                        </div>
                        @php
                            $contColuna++;
                        @endphp
        @endif
        @endforeach
        {{-- Fim foreach da matriz contendo o resultado --}}
        @php
            $contColuna = 1;
        @endphp
        @endforeach
        {{-- Fim foreach do grupo de temas --}}

    </div>

    </div>

    </div>

    @if ($tema->dsc_tema === '4. Orçamentário/Financeiro')
        @include('pac.financeiro.index')
    @endif

    </div>

    @php
        $contTema++;
    @endphp
    @endforeach
    {{-- Fim da montagem das tabs --}}

    </div>

    <div class="row">

        <div class="col-12 text-right pt-0">
            <div class="text-bg-light m-0 mb-3 p-0" style="font-size: 0.3rem!Important;">&nbsp;</div>
            <a href="{{ route('novo-pac') }}" class="btn btn-outline-secondary btn-sm">Voltar</a>
            <button id="saveButton" type="submit" class="btn btn-secondary btn-sm">Salvar</button>
        </div>

    </div>

    <script>
        function fncAbaSelecionada(aba_selecionada) {
            // Suponha que você queira adicionar uma query string ?item=valor à URL
            const newParam = "/" + aba_selecionada;

            // Obtenha a URL atual
            const currentUrl = window.location.href;
            const url = new URL(currentUrl);
            let pathname = url.pathname;

            // Divida o pathname em segmentos usando o separador '/'
            const segments = pathname.split('/');

            // Pegue a última parte não vazia do pathname
            const lastSegment = segments.pop() || segments.pop(); // Remove e pega o último item não vazio

            // Remova a última parte do pathname se terminar com '/div1', '/div2', '/div3', '/div4', ou '/div5'
            if (pathname.endsWith('/div1') || pathname.endsWith('/div2') || pathname.endsWith('/div3') || pathname.endsWith(
                    '/div4') || pathname.endsWith('/div5')) {
                pathname = pathname.substring(0, pathname.lastIndexOf('/'));
            }

            // Adicione o novo parâmetro ao pathname
            pathname += newParam;

            // Atualize o objeto URL com o novo pathname
            url.pathname = pathname;

            // Construa a nova URL
            const newUrl = url.toString();

            // Use history.pushState para atualizar a URL sem recarregar a página
            history.pushState(null, '', newUrl);

            // Verifique o segmento da aba selecionada e controle a exibição do botão "Salvar"
            checkLastSegment();
        }

        function checkLastSegment() {
            const urlPath = window.location.pathname;
            const segments = urlPath.split('/');
            const lastSegment = segments.pop() || segments.pop(); // Pega o último segmento

            // Verifica se o último segmento é diferente de 'div4'
            if (lastSegment !== 'div4') {
                document.getElementById('saveButton').style.display = 'inline-block';
            } else {
                document.getElementById('saveButton').style.display = 'none';
            }
        }

        // Verifica o segmento ao carregar a página
        window.onload = checkLastSegment;

        // Monitora mudanças na URL sem refresh da página
        window.onpopstate = checkLastSegment;
    </script>


    {!! Form::close() !!}
@endsection
