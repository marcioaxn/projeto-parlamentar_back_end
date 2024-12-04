<?php

function formatarDataComCarbonParaBR($data = '')
{
    Carbon\Carbon::setLocale('pt_BR');

    if ($data != '') {

        return Carbon\Carbon::parse($data)->format('d/m/Y');
    } else {

        return '';
    }
}

function formatarDataComCarbonParaEN($data = '')
{
    Carbon\Carbon::setLocale('pt_BR');

    if ($data != '') {

        return Carbon\Carbon::parse($data)->format('Y-m-d');
    } else {

        return '';
    }
}

function formatarTimeStampComCarbonParaEN($data = '')
{
    Carbon\Carbon::setLocale('pt_BR');

    if ($data != '') {

        return Carbon\Carbon::parse($data)->format('Y-m-d H:i:s');
    } else {

        return '';
    }
}

function formatarTimeStampComCarbonParaBR($data = '')
{
    Carbon\Carbon::setLocale('pt_BR');

    if ($data != '') {

        return Carbon\Carbon::parse($data)->format('d/m/Y à\\s H:i');
    } else {

        return '';
    }
}

function formatarTimeStampComCarbonParaBRSemPalavra($data = '')
{
    Carbon\Carbon::setLocale('pt_BR');

    if ($data != '') {

        return Carbon\Carbon::parse($data)->format('d/m/Y H:i');
    } else {

        return '';
    }
}

function formatarTimeStampComCarbonParaBRFormatoSimples($data = '')
{
    Carbon\Carbon::setLocale('pt_BR');

    if ($data != '') {

        //return Carbon\Carbon::parse($data)->format('d/m/Y H:i');
        return Carbon\Carbon::parse($data)->format('d/m/Y');
    } else {

        return '';
    }
}

function formatarDataComCarbonForHumans($data = '')
{

    if ($data != '') {

        Carbon\Carbon::setLocale('pt_BR');
        return Carbon\Carbon::parse($data)->diffForHumans();
    } else {

        return '';
    }
}

function converterTimeStamp($de, $para, $data = "0000-00-00")
{

    if ($data == "") {
        return "";
    } else {
        if ($de == "EN" && $para == "PTBR") {
            $dataVetor = explode("-", $data);

            if (count($dataVetor) > 2) {

                return $dataVetor[2] . "/" . $dataVetor[1] . "/" . $dataVetor[0];
            } else {

                return NULL;
            }
        } elseif ($de == "PTBR" && $para == "EN") {
            $dataVetor = explode("/", $data);

            if (count($dataVetor) > 2) {

                $parte = explode(' ', $dataVetor[2]);

                return $parte[0] . "-" . $dataVetor[1] . "-" . $dataVetor[0] . ' ' . $parte[1];
            } else {

                return NULL;
            }
        } else
            return $data;
    }
}

function tirarTimeStamp($data)
{

    $dataVetor = explode("-", $data);

    if (count($dataVetor) > 1) {

        $parte = explode(' ', $dataVetor[2]);

        if (count($parte) > 0) {

            return $dataVetor[0] . "-" . $dataVetor[1] . "-" . $parte[0];
        } else {

            return $dataVetor[0] . "-" . $dataVetor[1] . "-" . $dataVetor[2];
        }
    } else {

        return $data;
    }
}

function converterTimeStampParaNormal($de, $para, $data = "0000-00-00")
{

    if ($data == "") {
        return "";
    } else {
        if ($de == "EN" && $para == "PTBR") {
            $dataVetor = explode("-", $data);

            if (count($dataVetor) > 2) {

                return $dataVetor[2] . "/" . $dataVetor[1] . "/" . $dataVetor[0];
            } else {

                return NULL;
            }
        } elseif ($de == "PTBR" && $para == "EN") {
            $dataVetor = explode("/", $data);

            if (count($dataVetor) > 2) {

                $parte = explode(' ', $dataVetor[2]);

                return $parte[0] . "-" . $dataVetor[1] . "-" . $dataVetor[0];
            } else {

                return NULL;
            }
        } else
            return $data;
    }
}

function converterData($de, $para, $data = "0000-00-00")
{

    if ($data == "") {
        return "";
    } else {
        if ($de == "EN" && $para == "PTBR") {
            $dataVetor = explode("-", $data);

            if (count($dataVetor) > 2) {

                return $dataVetor[2] . "/" . $dataVetor[1] . "/" . $dataVetor[0];
            } else {

                return NULL;
            }
        } elseif ($de == "PTBR" && $para == "EN") {
            $dataVetor = explode("/", $data);

            if (count($dataVetor) > 2) {

                return $dataVetor[2] . "-" . $dataVetor[1] . "-" . $dataVetor[0];
            } else {

                return NULL;
            }
        } else
            return $data;
    }
}

function converterDataBrSimples($de, $para, $data = "0000-00-00")
{

    if ($data == "") {
        return "";
    } else {
        if ($de == "EN" && $para == "PTBR") {
            $dataVetor = explode("-", $data);
            return $dataVetor[2] . "/" . $dataVetor[1];
        } elseif ($de == "PTBR" && $para == "EN") {
            $dataVetor = explode("/", $data);
            return $dataVetor[2] . "-" . $dataVetor[1] . "-" . $dataVetor[0];
        } else

            return $data;
    }
}

function formatarValorFloatMysql($valor)
{

    return number_format($valor, 2, '.', '.');
}

function formatarValorFloatMysqlParaTresCasasAposPonto($valor)
{

    return number_format($valor, 3, '.', '.');
}


function detectarConverteValor($valor)
{

    $valor = str_replace(',', '.', $valor);

    return number_format((float) $valor, 2, ',', '.');

    return $valor;
}

function converteValor($de, $para, $valor)
{

    // Tenta realizar a conversão dentro de um bloco try-catch
    try {
        // Verifica se o valor é numérico e não uma string ou data
        if (isset($valor)) {
            if ($de === "MYSQL" && $para === "PTBR") {
                // Converte de formato MYSQL para PTBR
                $valor = str_replace(array(".", ","), array(",", "."), $valor);
                return number_format($valor, 2, ',', '.');
            } elseif ($de === "PTBR" && $para === "MYSQL") {
                // Converte de formato PTBR para MYSQL
                $valor = str_replace('.', "", $valor);
                $valor = str_replace(',', '.', $valor);
                return $valor;
            } else {
                // Retorna o valor sem alterações se as conversões não são especificadas
                return $valor;
            }
        } else {
            return null;
        }
    } catch (Exception $e) {
        // Captura a exceção e retorna uma mensagem de erro ou trata o erro de forma adequada
        return $valor;
    }
}

function converteValorSemCasasDecimais($de, $para, $valor)
{
    if ($valor == "") {
        return 0;
    } else {

        if ($de == "MYSQL" && $para == "PTBR") {
            $valor = str_replace(array(".", ","), array(",", "."), $valor);
            return number_format($valor, 0, ',', '.');
        } elseif ($de == "PTBR" && $para == "MYSQL") {
            $valor = str_replace('.', "", $valor);
            $valor = str_replace(',', '.', $valor);
            return $valor;
        } else {
            return $valor;
        }
    }
}

function formatarValor($valor = '')
{
    if (is_numeric($valor)) {
        $valor = str_replace(',', '.', $valor);
        return number_format($valor, 2, ',', '.');
    } else {
        $valor = $valor;
        return $valor;
    }
}

function formatarNumeroInteiro($valor = '')
{
    if (is_numeric($valor)) {
        $valor = str_replace(',', '.', $valor);
        return number_format($valor, 0, ',', '.');
    } else {
        $valor = $valor;
        return $valor;
    }
}

function date_forward($formato_entrada, $formato_saida, $data, $dias, $meses, $anos)
{
    if ($data == '')
        return '';

    if ($formato_entrada == 'EN') {
        $data = explode("-", $data);
        $ano = $data[0];
        $mes = $data[1];
        $dia = $data[2];
    } elseif ($formato_entrada == 'PTBR') {
        $data = explode("/", $data);
        $ano = $data[2];
        $mes = $data[1];
        $dia = $data[0];
    }

    $novaData = mktime(0, 0, 0, $mes + $meses, $dia + $dias, $ano + $anos);

    if ($formato_saida == 'EN')
        return strftime("%Y-%m-%d", $novaData);
    elseif ($formato_saida == 'PTBR')
        return strftime("%d/%m/%Y", $novaData);
}

function data_reward($formato_entrada, $formato_saida, $data, $dias, $meses, $anos)
{
    if ($data == '')
        return '';

    if ($formato_entrada == 'EN') {
        $data = explode("-", $data);
        $ano = $data[0];
        $mes = $data[1];
        $dia = $data[2];
    } elseif ($formato_entrada == 'PTBR') {
        $data = explode("/", $data);
        $ano = $data[2];
        $mes = $data[1];
        $dia = $data[0];
    }

    $novaData = mktime(0, 0, 0, $mes - $meses, $dia - $dias, $ano - $anos);

    if ($formato_saida == 'EN')
        return strftime("%Y-%m-%d", $novaData);
    elseif ($formato_saida == 'PTBR')
        return strftime("%d/%m/%Y", $novaData);
}

function formatarData($formato_entrada, $formato_saida, $data, $dias = 0, $meses = 0, $anos = 0)
{
    $data = '';
    $ano = '';
    $mes = '';
    $dia = '';
    if ($data == '')
        return '';

    if ($formato_entrada == 'EN') {
        $data = explode("-", $data);
        $ano = $data[0];
        $mes = $data[1];
        $dia = $data[2];
    } elseif ($formato_entrada == 'PTBR') {
        $data = explode("/", $data);
        $ano = $data[2];
        $mes = $data[1];
        $dia = $data[0];
    }

    $novaData = mktime(0, 0, 0, $mes - $meses, $dia - $dias, $ano - $anos);

    if ($formato_saida == 'EN')
        return strftime("%Y-%m-%d", $novaData);
    elseif ($formato_saida == 'PTBR')
        return strftime("%d/%m/%Y", $novaData);
}

function calcularDiferencaEntreDatas($dataFim = '')
{

    $datetime1 = new DateTime(date('Y-m-d'));
    $datetime2 = new DateTime($dataFim);
    $interval = $datetime1->diff($datetime2);
    return $interval->format('%R%a');
}

function gerar_senha($tamanho = 17, $maiusculas = true, $numeros = true, $simbolos = true)
{
    $lmin = '';
    $lmai = 'ABCDEFJKMPRZ';
    $num = '3579';
    $simb = '@#$%_';
    $retorno = '';
    $caracteres = '';

    $caracteres .= $lmin;
    if ($maiusculas)
        $caracteres .= $lmai;
    if ($numeros)
        $caracteres .= $num;
    if ($simbolos)
        $caracteres .= $simb;

    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand - 1];
    }
    return $retorno;
}

function date_convert($de, $para, $data)
{
    if ($data == '') {
        return '';
    } else {
        if ($de == 'EN' && $para == 'PTBR') {
            $dataVetor = explode('-', $data);
            return $dataVetor[2] . "/" . $dataVetor[1] . "/" . $dataVetor[0];
        } elseif ($de == 'PTBR' && $para == 'EN') {
            $dataVetor = explode('/', $data);
            return $dataVetor[2] . "-" . $dataVetor[1] . "-" . $dataVetor[0];
        } else
            return $data;
    }
}

function limpaNomeMinisterio($string)
{

    // matriz de entrada
    $what = array('Ministério da ', 'MINISTÉRIO DA ', 'Ministério do ', 'MINISTÉRIO DO ', 'Ministério de ', 'MINISTÉRIO DE ', 'Ministerio da ', 'MINISTERIO DA ', 'Ministerio do ', 'MINISTERIO DO ', 'Ministerio de ', 'MINISTERIO DE ');

    // matriz de saída
    $by = array('', '', '', '', '', '', '', '', '', '', '', '');

    // devolver a string
    return str_replace($what, $by, $string);
}

function limpaString($string)
{

    // matriz de entrada
    $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'Ã', 'Â', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º', '.', "d'", "D", '.', '-', "'");

    // matriz de saída
    $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'N', 'c', 'C', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', "d", "D", '', '', "");

    // devolver a string
    return str_replace($what, $by, $string);
}

function tirarAcentuacao($string)
{

    // matriz de entrada
    $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'Ã', 'Â', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', 'Õ');

    // matriz de saída
    $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'N', 'c', 'C', 'O');

    // devolver a string
    return str_replace($what, $by, $string);
}

function limpaStringSemTirarHifem($string)
{

    // matriz de formato_entrada
    $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'Ã', 'Â', 'É', 'Ê', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', 'Ô', 'Õ', "D'A", "T'A");

    // matriz de saída
    $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'A', 'A', 'E', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', 'O', 'O', "DA", "TA");

    // devolver a string
    return str_replace($what, $by, $string);
}

function limpa($string)
{

    // matriz de entrada
    $what = array('-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º', '.');

    // matriz de saída
    $by = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

    // devolver a string
    return str_replace($what, $by, $string);
}

function tiraApostrofo($string = '')
{

    $what = array("D'", "d'");

    $by = array('D ', 'd ');

    return str_replace($what, $by, $string);
}

function mesExtensoParaNumeral($mesExtenso)
{
    switch ($mesExtenso) {
        case 'JANEIRO':
            $mesNumeral = '01';
            break;
        case 'FEVEREIRO':
            $mesNumeral = '02';
            break;
        case 'MARÇO':
            $mesNumeral = '03';
            break;
        case 'ABRIL':
            $mesNumeral = '04';
            break;
        case 'MAIO':
            $mesNumeral = '05';
            break;
        case 'JUNHO':
            $mesNumeral = '06';
            break;
        case 'JULHO':
            $mesNumeral = '07';
            break;
        case 'AGOSTO':
            $mesNumeral = '08';
            break;
        case 'SETEMBRO':
            $mesNumeral = '09';
            break;
        case 'OUTUBRO':
            $mesNumeral = '10';
            break;
        case 'NOVEMBRO':
            $mesNumeral = '11';
            break;
        case 'DEZEMBRO':
            $mesNumeral = '12';
            break;
    }

    return $mesNumeral;
}

function mesNumeralParaExtensoCurto($valor)
{

    switch ($valor) {
        case 1;
            $mes = "Jan";
            break;
        case 2;
            $mes = "Fev";
            break;
        case 3;
            $mes = "Mar";
            break;
        case 4;
            $mes = "Abr";
            break;
        case 5;
            $mes = "Mai";
            break;
        case 6;
            $mes = "Jun";
            break;
        case 7;
            $mes = "Jul";
            break;
        case 8;
            $mes = "Ago";
            break;
        case 9;
            $mes = "Set";
            break;
        case 10;
            $mes = "Out";
            break;
        case 11;
            $mes = "Nov";
            break;
        case 12;
            $mes = "Dez";
            break;
    }

    return $mes;
}

function mesNumeralParaExtenso($mes)
{

    $mesNumeral = '';

    switch ($mes) {
        case '1':
            $mesNumeral = 'Janeiro';
            break;
        case '2':
            $mesNumeral = 'Fevereiro';
            break;
        case '3':
            $mesNumeral = 'Março';
            break;
        case '4':
            $mesNumeral = 'Abril';
            break;
        case '5':
            $mesNumeral = 'Maio';
            break;
        case '6':
            $mesNumeral = 'Junho';
            break;
        case '7':
            $mesNumeral = 'Julho';
            break;
        case '8':
            $mesNumeral = 'Agosto';
            break;
        case '9':
            $mesNumeral = 'Setembro';
            break;
        case '10':
            $mesNumeral = 'Outubro';
            break;
        case '11':
            $mesNumeral = 'Novembro';
            break;
        case '12':
            $mesNumeral = 'Dezembro';
            break;
    }

    return $mesNumeral;
}

function siglaOrgao($orgao = '')
{

    switch ($orgao) {
        case 'CONSELHO NACIONAL DE JUSTIÇA':
            $sigla = 'CNJ';
            break;
        case 'INST.FED.DE EDUC.,CIENC.E TEC.DE MINAS GERAIS':
            $sigla = 'IFMG';
            break;
        case 'MINISTERIO DOS DIREITOS HUMANOS':
            $sigla = 'MDH';
            break;
        case 'MIN.DAS MULH., DA IG.RACIAL E DOS DIR.HUMANOS':
            $sigla = 'MDMIRDH';
            break;
        case 'MINIST. DA INDUSTRIA, COM.EXTERIOR E SERVICOS':
            $sigla = 'MDIC';
            break;
        case 'MINIST.DOS TRANSP.,PORTOS E AVIACAO CIVIL':
            $sigla = 'MTPAC';
            break;
        case 'MINISTERIO DO TRABALHO E EMPREGO':
            $sigla = 'MTB';
            break;
        case 'MINISTERIO DO TRABALHO E PREVIDENCIA SOCIAL':
            $sigla = 'MTPS';
            break;
        case 'MINISTÉRIO DA AGRICULTURA, PECUARIA E ABASTECIMENTO':
            $sigla = 'MAPA';
            break;
        case 'MINISTÉRIO DA CIÊNCIA, TECNOLOGIA, INOVAÇÕES E COMUNICAÇÕES':
            $sigla = 'MCTIC';
            break;
        case 'MINISTERIO DA CULTURA':
            $sigla = 'CULTURA';
            break;
        case 'PRESIDẼNCIA DA REPÚBLICA':
            $sigla = 'PR';
            break;
        case 'PRESIDENCIA DA REPÚBLICA':
            $sigla = 'PR';
            break;
        case 'MINISTERIO DA DEFESA':
            $sigla = 'DEFESA';
            break;
        case 'MINISTERIO DA EDUCACAO':
            $sigla = 'MEC';
            break;
        case 'MINISTERIO DA FAZENDA':
            $sigla = 'FAZENDA';
            break;
        case 'MINISTERIO DA INTEGRACAO NACIONAL':
            $sigla = 'INTEGRACAO';
            break;
        case 'MINISTERIO DA JUSTICA':
            $sigla = 'JUSTICA';
            break;
        case 'MINISTÉRIO DA PESCA E AQUICULTURA':
            $sigla = 'MPA';
            break;
        case 'MINISTERIO DA SAUDE':
            $sigla = 'SAUDE';
            break;
        case 'MINISTÉRIO DA TRANSPARÊNCIA E CONTROLADORIA-GERAL DA UNIÃO':
            $sigla = 'CGU';
            break;
        case 'MINISTERIO DAS CIDADES':
            $sigla = 'CIDADES';
            break;
        case 'MINISTERIO DAS COMUNICACOES':
            $sigla = 'COMUNICACOES';
            break;
        case 'MINISTERIO DAS RELACOES EXTERIORES':
            $sigla = 'MRE';
            break;
        case 'MINISTERIO DE MINAS E ENERGIA':
            $sigla = 'MME';
            break;
        case 'MINISTERIO DO DESENVOLVIMENTO AGRARIO':
            $sigla = 'MDA';
            break;
        case 'MINISTERIO DO DESENVOLVIMENTO SOCIAL':
            $sigla = 'MDS';
            break;
        case 'MINISTERIO DO ESPORTE':
            $sigla = 'ESPORTE';
            break;
        case 'MINISTERIO DO TURISMO':
            $sigla = 'TURISMO';
            break;
        case 'MINISTERIO DO MEIO AMBIENTE':
            $sigla = 'MMA';
            break;
        case 'MINISTERIO DO DESENVOLVIMENTO REGIONAL':
            $sigla = 'MDR';
            break;
        case 'MINISTERIO DA CIDADANIA':
            $sigla = 'CIDADANIA';
            break;
        case 'MINISTERIO DA JUSTICA E SEGURANCA PUBLICA':
            $sigla = 'JUSTIÇA';
            break;
        case 'MINIST. DA CIENCIA, TECNOL., INOV. E COMUNICACOES':
            $sigla = 'MCTIC';
            break;
        case 'MINIST. MULHER, FAMILIA E DIREITOS HUMANOS':
            $sigla = 'MDH';
            break;

        default:
            $sigla = $orgao;
            break;
    }
    return $sigla;
}

function siglaMinisterio($ministerio = '')
{

    switch ($ministerio) {
        case 'Ministério da Agricultura, Pecuária e Abastec':
            $sigla = 'Agricultura';
            break;
        case 'Ministério do Desenvolvimento Social':
            $sigla = 'MDS';
            break;
        case 'Ministério da Infraestrutura':
            $sigla = 'Infraestrutura';
            break;
        case 'Ministério da Fazenda':
            $sigla = 'Economia';
            break;
        case 'Ministério do Turismo':
            $sigla = 'MTUR';
            break;
        case 'Ministério da Mulher, Família e Direitos Huma':
            $sigla = 'MDH';
            break;
        case 'Ministério das Relações Exteriores':
            $sigla = 'MRE';
            break;
        case 'Ministério da Justiça e Segurança Pública':
            $sigla = 'Justiça';
            break;
        case 'Ministério do Meio Ambiente':
            $sigla = 'MMA';
            break;
        case 'Ministério da Saúde':
            $sigla = 'Saúde';
            break;
        case 'Ministério do Desenvolvimento Regional':
            $sigla = 'MDR';
            break;
        case 'Ministério de Minas e Energia':
            $sigla = 'MME';
            break;
        case 'Ministério da Ciência, Tecnologia, Inovações ':
            $sigla = 'MCTIC';
            break;
        case 'Ministério da Educação':
            $sigla = 'Educação';
            break;
        case 'Ministério da Defesa':
            $sigla = 'Defesa';
            break;

        default:
            $sigla = $ministerio;
            break;
    }
    return $sigla;
}

function abreviarSituacaoConvenio($situacao = '')
{

    switch ($situacao) {
        case 'Aguardando Prestação de Contas':
            $situacaoAbreviada = 'Aguardar P. Contas';
            break;
        case 'Assinatura Pendente Registro TV Siafi':
            $situacaoAbreviada = 'Assin. Pend. Regist. TV SIAFI';
            break;
        case 'Cancelado':
            $situacaoAbreviada = 'Cancelado';
            break;
        case 'Convênio Anulado':
            $situacaoAbreviada = 'Convênio Anulado';
            break;
        case 'Convênio Rescindido':
            $situacaoAbreviada = 'Convênio Rescindido';
            break;
        case 'Em execução':
            $situacaoAbreviada = 'Em execução';
            break;
        case 'Inadimplente':
            $situacaoAbreviada = 'Inadimplente';
            break;
        case 'Prestação de Contas Aprovada':
            $situacaoAbreviada = 'P. Contas Aprovada';
            break;
        case 'Prestação de Contas Aprovada com Ressalvas':
            $situacaoAbreviada = 'P. Contas Aprov. Ressalvas';
            break;
        case 'Prestação de Contas Comprovada em Análise':
            $situacaoAbreviada = 'P. Contas Comprov. em Análise';
            break;
        case 'Prestação de Contas Concluída':
            $situacaoAbreviada = 'P. Contas Concluída';
            break;
        case 'Prestação de Contas em Análise':
            $situacaoAbreviada = 'P. Contas em Análise';
            break;
        case 'Prestação de Contas em Complementação':
            $situacaoAbreviada = 'P. Contas Complementação';
            break;
        case 'Prestação de Contas enviada para Análise':
            $situacaoAbreviada = 'P. Contas Enviada Análise';
            break;
        case 'Prestação de Contas Iniciada Por Antecipação':
            $situacaoAbreviada = 'P. Contas Iniciada Antecipação';
            break;
        case 'Prestação de Contas Rejeitada':
            $situacaoAbreviada = 'P. Contas Rejeitada';
            break;
        case 'Proposta/Plano de Trabalho Aprovado':
            $situacaoAbreviada = 'Prop./Pl. Trab. Aprovada';
            break;
        case 'Proposta/Plano de Trabalho Complementado em Análise':
            $situacaoAbreviada = 'Prop./Pl. Trab. Complem. em Análise';
            break;
        case 'Proposta/Plano de Trabalho Complementado Enviado para Análise':
            $situacaoAbreviada = 'Prop./Pl. Trab. Complem. Enviadp Análise';
            break;
        default:
            $situacaoAbreviada = $situacao;
            break;
    }

    return $situacaoAbreviada;
}

function legenda()
{
    ?>
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
            <h6>Legenda:</h6>
            <ul class="list-group ">
                <li class="list-group-item bg-primary"
                    style="background-color: #9FCDFF !Important; padding: 3px !Important; padding-left: 17px !Important;">
                    Contrato/convênio concluído</li>
            </ul>
            <small class="text-primary">Contrato de Repasse/Convênio não está mais ativo</small>
        </div>

        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
            <h6>&nbsp;</h6>
            <ul class="list-group ">
                <li class="list-group-item bg-success text-white"
                    style="background-color: #4CA746 !Important; color: #FFFFFF !Important;padding: 3px !Important; padding-left: 17px !Important;">
                    Em execução, mas não precisa mais de Financeiro</li>
            </ul>
            <small class="text-success">Célula da coluna Valor Desembolsado</small>
        </div>

        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
            <h6>&nbsp;</h6>
            <ul class="list-group ">
                <li class="list-group-item bg-danger text-white"
                    style="background-color: #df4957 !Important; color: #FFFFFF !Important;padding: 3px !Important; padding-left: 17px !Important;">
                    Contrato/convênio cancelado ou anulado</li>
            </ul>
            <small class="text-danger">Toda a fonte da linha na cor vermelha</small>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            &nbsp;
        </div>

        <!-- <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
            <h6>Legenda coluna: <b>Vigência</b></h6>
            <ul class="list-group ">
                <li class="list-group-item bg-success text-white" style="background-color: #7eca8f !Important; color: #FFFFFF !Important; padding: 3px !Important; padding-left: 17px !Important;">30 a 45 dias para vencer => Verde</li>
                <li class="list-group-item bg-warning text-dark" style="background-color: #ffd96a !Important; color: #000000 !Important;padding: 3px !Important; padding-left: 17px !Important;">15 a 29 dias para vencer => Laranja</li>
                <li class="list-group-item bg-danger text-white" style="background-color: #df4957 !Important; color: #FFFFFF !Important;padding: 3px !Important; padding-left: 17px !Important;">Até 14 dias para vencer => Vermelha</li>
                <li class="list-group-item bg-secondary text-white" style="background-color: #a6acb1 !Important; color: #000000 !Important;padding: 3px !Important; padding-left: 17px !Important;">Vigência vencida => Cinza</li>
            </ul>
        </div> -->

        <!-- <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
            <h6>(*) <b>Possível valor apto</b></h6>
            <ul class="list-group ">
                <li class="list-group-item bg-light text-dark">Informação em fase de análise, conforme Portaria Interministerial Nº 424, de 30/12/2016</li>
            </ul>
        </div> -->
    </div>
    <?php
}

function abreviacaoCasaParlamentar($casa = '')
{

    switch ($casa) {
        case 'Deputado':
            $abreviacaoCasa = 'DEP ';
            break;
        case 'Deputada':
            $abreviacaoCasa = 'DEP ';
            break;
        case 'Senador':
            $abreviacaoCasa = 'SEN ';
            break;
        case 'Senadora':
            $abreviacaoCasa = 'SEN ';
            break;
        case 'Governador':
            $abreviacaoCasa = 'Gov ';
            break;

        default:
            $abreviacaoCasa = $casa;
            break;
    }

    return $abreviacaoCasa;
}

function ajustarBarra($texto = '')
{

    $texto = str_replace("/", ".DIRECTORY_SEPARATOR.", $texto);

    return $texto;
}

function ajustarBarraRoute($texto = '')
{

    $texto = str_ireplace("/", "alt47", $texto);

    return $texto;
}

function trocarVirgulaPorBarra($texto = '')
{

    $texto = str_replace(",", " <b>/</b> ", $texto);

    return $texto;
}

function abreviacaoCargos($cargo = '')
{

    $abreviacao = '';

    switch ($cargo) {
        case '1ª Procuradora Adjunta':
            $abreviacao = '1ªProc_Adj';
            break;
        case 'Presidente':
            $abreviacao = 'P';
            break;
        case 'Ouvidor-Geral':
            $abreviacao = 'Ouv_Ger';
            break;
        case 'Relator-Parcial':
            $abreviacao = 'Rel_Parc';
            break;
        case '2ª Procuradora Adjunta':
            $abreviacao = '2ªProc_Adj';
            break;
        case 'Coordenadora':
            $abreviacao = 'Coord';
            break;
        case 'Secretário de Transparência':
            $abreviacao = 'Sec_Transp';
            break;
        case 'Corregedor':
            $abreviacao = 'Correg';
            break;
        case '2º Secretário Adjunto':
            $abreviacao = '2ºSec_Adj';
            break;
        case '4º Suplente de Secretário':
            $abreviacao = '4ºSupl_Sec';
            break;
        case 'Procurador':
            $abreviacao = 'Proc';
            break;
        case '3ª Procuradora Adjunta':
            $abreviacao = '3ªProc_Adj';
            break;
        case 'Relator-Geral':
            $abreviacao = 'Rel_Geral';
            break;
        case '2º Secretário':
            $abreviacao = '2ºSec';
            break;
        case '1º Secretário':
            $abreviacao = '1ºSec';
            break;
        case '3º Secretário':
            $abreviacao = '3ºSec';
            break;
        case '3º Secretário Adjunto':
            $abreviacao = '3ºSec_Adj';
            break;
        case 'Secretário de Comunicação Social':
            $abreviacao = 'SECOM';
            break;
        case '4º Secretário':
            $abreviacao = '4ºSec';
            break;
        case '1º Secretário Adjunto':
            $abreviacao = '1ºSec_Adj';
            break;
        case 'Sub-Relator':
            $abreviacao = 'Sub_Rel';
            break;
        case '2ª Coordenadora Adjunta':
            $abreviacao = '2ªCoord_Adj';
            break;
        case '3º Suplente de Secretário':
            $abreviacao = '3ºSupl_Sec';
            break;
        case '1º Vice-Presidente':
            $abreviacao = '1ºV_P';
            break;
        case 'Procuradora':
            $abreviacao = 'Proc';
            break;
        case 'Coordenador':
            $abreviacao = 'Coord';
            break;
        case '2º Suplente de Secretário':
            $abreviacao = '2ºSupl_Sec';
            break;
        case '2º Vice-Presidente':
            $abreviacao = '2ºV_P';
            break;
        case 'Secretário de Relações Internacionais':
            $abreviacao = 'SecRelInter';
            break;
        case 'Relator':
            $abreviacao = 'Rel';
            break;
        case '3º Vice-Presidente':
            $abreviacao = '3ºV_P';
            break;
        case 'Vice-Presidente':
            $abreviacao = 'V_P';
            break;
        case 'Sec de Part Inter e Mídias Digitais':
            $abreviacao = 'SecPartInterMídDig';
            break;
        case '1ª Coordenadora Adjunta':
            $abreviacao = '1ªCoord_Adj';
            break;
        case '1º Suplente de Secretário':
            $abreviacao = '1ºSupl_Sec';
            break;
        case '3ª Coordenadora Adjunta':
            $abreviacao = '3ªCoord_Adj';
            break;
        case 'Vice-Coordenador':
            $abreviacao = 'V_Coord';
            break;

        case 'COORDENADOR':
            $abreviacao = 'Coord';
            break;
        case '2ª VICE-PRESIDENTE':
            $abreviacao = '2ªV_P';
            break;
        case 'OUVIDOR-GERAL':
            $abreviacao = 'Ouv_Ger';
            break;
        case 'RELATOR':
            $abreviacao = 'Rel';
            break;
        case 'PRESIDENTE':
            $abreviacao = 'P';
            break;
        case 'Relator da Receita':
            $abreviacao = 'Rel_Rec';
            break;
        case 'CORREGEDOR':
            $abreviacao = 'Correg';
            break;
        case '1ª VICE-PRESIDENTE':
            $abreviacao = '1ªV_P';
            break;
        case 'VICE-PRESIDENTE':
            $abreviacao = 'V_P';
            break;
        case 'RELATORA':
            $abreviacao = 'Rel';
            break;
        case '2º VICE-PRESIDENTE':
            $abreviacao = '2ºV_P';
            break;
        case 'Relator do Projeto de Plano Plurianual':
            $abreviacao = 'RelProjPlPlu';
            break;
        case 'GRÃO-MESTRE':
            $abreviacao = 'GRÃO-MESTRE';
            break;


        default:
            $abreviacao = $cargo;
            break;
    }

    return $abreviacao;
}

function estadoPorUf($estado = '')
{

    switch ($estado) {
        case 'Acre':
            $uf = 'AC';
            break;

        case 'Alagoas':
            $uf = 'AL';
            break;

        case 'Amazonas':
            $uf = 'AM';
            break;

        case 'Amapá':
            $uf = 'AP';
            break;

        case 'Bahia':
            $uf = 'BA';
            break;

        case 'Ceará':
            $uf = 'CE';
            break;

        case 'Distrito Federal':
            $uf = 'DF';
            break;

        case 'Espírito Santo':
            $uf = 'ES';
            break;

        case 'Goiás':
            $uf = 'GO';
            break;

        case 'Maranhão':
            $uf = 'MA';
            break;

        case 'Minas Gerais':
            $uf = 'MG';
            break;

        case 'Mato Grosso do Sul':
            $uf = 'MS';
            break;

        case 'Mato Grosso':
            $uf = 'MT';
            break;

        case 'Pará':
            $uf = 'PA';
            break;

        case 'Paraíba':
            $uf = 'PB';
            break;

        case 'Pernambuco':
            $uf = 'PE';
            break;

        case 'Piauí':
            $uf = 'PI';
            break;

        case 'Paraná':
            $uf = 'PR';
            break;

        case 'Rio de Janeiro':
            $uf = 'RJ';
            break;

        case 'Rio Grande do Norte':
            $uf = 'RN';
            break;

        case 'Rondônia':
            $uf = 'RO';
            break;

        case 'Roraima':
            $uf = 'RR';
            break;

        case 'Rio Grande do Sul':
            $uf = 'RS';
            break;

        case 'Santa Catarina':
            $uf = 'SC';
            break;

        case 'Sergipe':
            $uf = 'SE';
            break;

        case 'São Paulo':
            $uf = 'SP';
            break;

        case 'Tocantins':
            $uf = 'TO';
            break;




        default:
            $uf = $estado;
            break;
    }

    return $uf;
}

function nomeCampoNormalizado($campo)
{

    $campoNormalizado = '';

    switch ($campo) {
        case 'instrumento_ativo':
            $campoNormalizado = 'Instrumento ativo';
            break;
        case 'data_retirada_suspensiva':
            $campoNormalizado = 'Data de retirada da suspensiva';
            break;
        case 'dia_fim_vigenc_conv':
            $campoNormalizado = 'Data fim da vigẽncia';
            break;
        case 'motivo_suspensao':
            $campoNormalizado = 'Motivo da suspensão';
            break;
        case 'percentual_financeiro_desbloqueado':
            $campoNormalizado = 'Percentual financeiro desbloqueado';
            break;
        case 'percentual_fisico_aferido':
            $campoNormalizado = 'Percentual físico aferido';
            break;
        case 'permite_liberar_primeiro_repasse_projeto':
            $campoNormalizado = 'Permite liberar primeiro repasse do projeto';
            break;
        case 'status_analise_siconv':
            $campoNormalizado = 'Status do SICONV após análise do MDR';
            break;
        case 'vl_empenhado_conv':
            $campoNormalizado = 'Valor empenhado';
            break;
        case 'vl_contrapartida_conv':
            $campoNormalizado = 'Valor da contrapartida';
            break;
        case 'vl_desembolsado_conv':
            $campoNormalizado = 'Valor desembolsado';
            break;
        case 'motivo_suspensao':
            $campoNormalizado = 'Motivo da suspensão';
            break;
        case 'sit_convenio':
            $campoNormalizado = 'Situação do convênio/contrato';
            break;
        case 'subsituacao_conv':
            $campoNormalizado = 'Subsituação do convênio/contrato';
            break;

        default:
            $campoNormalizado = $campo;
            break;
    }

    return $campoNormalizado;
}

function nomeCampoNormalizadoTabAtendimento($campo)
{

    $campoNormalizado = '';

    switch ($campo) {
        case 'cod_interlocutor':
            $campoNormalizado = 'Interlocutor(a)';
            break;
        case 'nom_interlocutor':
            $campoNormalizado = 'Nome do(a) Interlocutor(a)';
            break;
        case 'cod_parlamentar':
            $campoNormalizado = '';
            break;
        case 'cod_assunto':
            $campoNormalizado = 'Assunto';
            break;
        case 'cod_cargo':
            $campoNormalizado = 'Foi recebido(a) pelo(a)';
            break;
        case 'dte_atendimento':
            $campoNormalizado = 'Data do atendimento';
            break;
        case 'bln_representante':
            $campoNormalizado = 'Atendimento foi feito a um representante?';
            break;
        case 'nom_representante':
            $campoNormalizado = 'Nome do(a) Representante';
            break;
        case 'dsc_cargo_representante':
            $campoNormalizado = 'Cargo do(a) Representante';
            break;
        case 'txt_convidados':
            $campoNormalizado = 'Convidado(s)';
            break;
        case 'nom_convidado':
            $campoNormalizado = 'Nome convidado(a)';
            break;
        case 'dsc_demanda':
            $campoNormalizado = 'Descrição da demanda';
            break;
        case 'codigoUnidade':
            $campoNormalizado = 'Área Responsável pela demanda';
            break;
        case 'dte_prazo':
            $campoNormalizado = 'Prazo estimado de concluir a demanda';
            break;
        case 'cod_status_demanda':
            $campoNormalizado = 'Status da demanda';
            break;
        case 'txt_assunto':
            $campoNormalizado = 'Assunto do arquivo';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;

        default:
            $campoNormalizado = $campo;
            break;
    }

    return $campoNormalizado;
}

function nomeCampoTabVisMdrNormalizado($campo)
{

    $campoNormalizado = '';

    switch ($campo) {
        case 'NPAC_DBGESTORES':
            $campoNormalizado = 'Contratos de Repasse';
            break;
        case 'NPAC_FGTS':
            $campoNormalizado = 'FGTS';
            break;
        case 'NPAC_SICONV':
            $campoNormalizado = 'Convênios';
            break;
        case 'PAC_MCID':
            $campoNormalizado = 'PAC Min. Cidades';
            break;
        case 'PAC_MI':
            $campoNormalizado = 'PAC Min. Integração';
            break;
        case 'origem':
            $campoNormalizado = 'Origem dos Instrumentos';
            break;
        case 'txt_sigla_area':
            $campoNormalizado = 'Área(s) do MDR';
            break;
        case 'Contratado - concluído':
            $campoNormalizado = 'Concluído';
            break;
        case 'Contratado - normal':
            $campoNormalizado = 'Normal';
            break;
        case 'Contratado - suspensiva':
            $campoNormalizado = 'Suspensiva';
            break;
        case 'Contratado - em Prestação de Contas':
            $campoNormalizado = 'Em Prestação de Contas';
            break;
        case 'Contratado - em TCE':
            $campoNormalizado = 'Em TCE';
            break;
        case 'Contratado - liminar':
            $campoNormalizado = 'Liminar';
            break;
        case 'Contratado – suspensiva e liminar':
            $campoNormalizado = 'Suspensiva e Liminar';
            break;
        case 'periodoInicial':
            $campoNormalizado = 'Período';
            break;
        case 'dsc_situacao_contrato_mdr':
            $campoNormalizado = 'Situação do Instrumento MDR';
            break;
        case 'uf':
            $campoNormalizado = 'UF';
            break;
        case 'cod_mdr':
            $campoNormalizado = 'Código MIDR';
            break;
        case 'cod_cipi':
            $campoNormalizado = 'Código CIPI <small class="text-muted">(Cadastro Integrado de Projetos de Investimento)</small>';
            break;
        case 'num_processo_sei':
            $campoNormalizado = 'Número do Processo SEI';
            break;
        case 'cod_s2id':
            $campoNormalizado = 'Código do Sistema S2iD';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case 'numero_generico_contrato':
            $campoNormalizado = 'Outro Número do Instrumento';
            break;
        case 'municipio':
            $campoNormalizado = 'Município';
            break;
        case 'ibge':
            $campoNormalizado = 'Código IBGE';
            break;
        case 'codigo_saci':
            $campoNormalizado = 'Código SACI';
            break;
        case 'nm_convenio_siafi':
            $campoNormalizado = 'Número Convênio SIAFI';
            break;
        case 'tipo_instrumento':
            $campoNormalizado = 'Tipo do Instrumento';
            break;
        case 'dsc_agente_financeiro':
            $campoNormalizado = 'Agente Financeiro';
            break;
        case 'fonte':
            $campoNormalizado = 'Fonte';
            break;
        case 'concedente':
            $campoNormalizado = 'Concedente';
            break;
        case 'bln_ativo':
            $campoNormalizado = 'Instrumento Ativo';
            break;
        case 'dsc_modalidade':
            $campoNormalizado = 'Modalidade';
            break;
        case 'dsc_situacao_contrato':
            $campoNormalizado = 'Situação do Contrato';
            break;
        case 'dsc_situacao_obra':
            $campoNormalizado = 'Situação da Obra';
            break;
        case 'txt_empreendimento':
            $campoNormalizado = 'Nome do Empreendimento';
            break;
        case 'prc_execucao':
            $campoNormalizado = 'Percentual de Execução Financeira';
            break;
        case 'prc_execucao_fisica':
            $campoNormalizado = 'Percentual de Execução Física';
            break;
        case 'prc_empenhado':
            $campoNormalizado = 'Percentual de Empenhado';
            break;
        case 'prc_desembolsado':
            $campoNormalizado = 'Percentual de Desembolso';
            break;
        case 'prc_desbloqueado':
            $campoNormalizado = 'Percentual de Desbloqueio';
            break;
        case 'vlr_investimento':
            $campoNormalizado = 'Valor de Investimento';
            break;
        case 'vlr_repasse':
            $campoNormalizado = 'Valor de Repasse';
            break;
        case 'vlr_contrapartida':
            $campoNormalizado = 'Valor Contrapartida';
            break;
        case 'vlr_pago_conta':
            $campoNormalizado = 'Valor Desembolsado/Liberado';
            break;
        case 'vlr_desbloqueado_vr':
            $campoNormalizado = 'Valor Desbloqueado';
            break;
        case 'funcional':
            $campoNormalizado = 'Funcional';
            break;
        case 'dsc_tomador':
            $campoNormalizado = 'Tomador';
            break;
        case 'bln_carteira_mdr':
            $campoNormalizado = 'Carteira MDR';
            break;
        case 'bln_carteira_mdr_ativo':
            $campoNormalizado = 'Carteira MDR Ativa';
            break;
        case 'dsc_situacao_objeto_mdr':
            $campoNormalizado = 'Situação do Objeto MDR';
            break;
        case 'vlr_empenhado':
            $campoNormalizado = 'Valor Empenhado';
            break;
        case 'dte_carga':
            $campoNormalizado = 'Data de Atualização da Carga';
            break;
        case 'cod_contrato':
            $campoNormalizado = 'Número do Instrumento';
            break;
        case 'cod_pt':
            $campoNormalizado = 'Código do Plano de Trabalho';
            break;
        case 'cod_ag_operador':
            $campoNormalizado = 'Cód. Agente Operador';
            break;
        case 'cod_id_proposta':
            $campoNormalizado = 'ID Proposta';
            break;
        case 'cod_nr_proposta':
            $campoNormalizado = 'Número da Proposta';
            break;
        case 'siconv_instrumento_ativo':
            $campoNormalizado = 'SICONV Instrumento Ativo';
            break;
        case 'siconv_convenio_assinado':
            $campoNormalizado = 'Convênio Assinado';
            break;
        case 'dte_assinatura_contrato':
            $campoNormalizado = 'Data Assinatura Contrato';
            break;
        case 'dte_inicio_obra_efetiva':
            $campoNormalizado = 'Data de Início (Realizado)';
            break;
        case 'dte_fim_obra':
            $campoNormalizado = 'Data do Fim (Realizado)';
            break;
        case 'dte_ult_mov_fin':
            $campoNormalizado = 'Data da Última Movimentação Financeira';
            break;
        case 'dsc_situacao_contrato_compl':
            $campoNormalizado = 'Sit. do Contrato Complemento';
            break;
        case 'siconv_dsc_sit_convenio':
            $campoNormalizado = 'SICONV Situação do Convênio';
            break;
        case 'siconv_dsc_sit_contratacao':
            $campoNormalizado = 'SICONV Situação da Contratação';
            break;
        case 'siconv_dsc_subsit_convenio':
            $campoNormalizado = 'SICONV Subsituação do Convênio';
            break;
        case 'siconv_dsc_sit_proposta':
            $campoNormalizado = 'SICONV Situação da Proposta';
            break;
        case 'dsc_motivo_paralisacao':
            $campoNormalizado = 'Motivo da Paralisação';
            break;
        case 'par_348':
            $campoNormalizado = 'Portaria 348';
            break;
        case 'avancar_cidades':
            $campoNormalizado = 'Avançar Cidades';
            break;
        case 'bln_emenda':
            $campoNormalizado = 'É emenda';
            break;
        case 'vlr_pago':
            $campoNormalizado = 'Valor Pago';
            break;
        case 'dsc_paralisada_mdr':
            $campoNormalizado = 'Paralisada MDR';
            break;
        case 'bln_pro_brasil':
            $campoNormalizado = 'Pró Brasil';
            break;
        case 'bln_mais_nordeste':
            $campoNormalizado = 'Mais Nordeste';
            break;
        case 'bln_revitalizacao_bacias':
            $campoNormalizado = 'Revitalização de Bacias';
            break;
        case 'qtd_dias_ult_mov_fin':
            $campoNormalizado = 'Quantidade de dias desde a última movimentação financeira';
            break;
        case 'e_pac':
            $campoNormalizado = 'Fez parte do PAC';
            break;
        case 'num_cnpj_tomador':
            $campoNormalizado = 'CNPJ do tomador';
            break;
        case 'dte_inicio_obra_prevista':
            $campoNormalizado = 'Data de Início (Previsto)';
            break;
        case 'dte_fim_obra_prevista':
            $campoNormalizado = 'Data do Fim (Previsto)';
            break;
        case 'dte_ano_conclusao_previsto':
            $campoNormalizado = 'Ano Previsto de Cnclusão';
            break;
        case 'vlr_restos_a_pagar':
            $campoNormalizado = 'Valor de Resto a Pagar';
            break;
        case 'vlr_anual_conclusao_previsto':
            $campoNormalizado = 'Valor para Conclusão Anual (Conforme CIPI)';
            break;
        case 'bln_apto_inauguracao':
            $campoNormalizado = 'Entrega Confirmada?';
            break;
        case 'dte_inauguracao':
            $campoNormalizado = 'Data da Entrega (Realizado)';
            break;
        case 'dsc_familias_beneficiadas':
            $campoNormalizado = 'Descrição das Famílias Beneficiadas';
            break;
        case 'num_familias_beneficiadas':
            $campoNormalizado = 'Número das Famílias Beneficiadas';
            break;
        case 'trimestre':
            $campoNormalizado = 'Trimestre';
            break;
        case 'mes_previsto':
            $campoNormalizado = 'Mês Previsto da Entrega';
            break;
        case 'mes':
            $campoNormalizado = 'Mês Efetivo da Entrega';
            break;
        case 'tipo_objeto':
            $campoNormalizado = 'Classificação por Eixo, Tipo e Subtipo';
            break;
        case 'ano_previsto':
            $campoNormalizado = 'Ano Previsto da Entrega';
            break;
        case 'ano':
            $campoNormalizado = 'Ano Efetivo da Entrega';
            break;
        case 'dte_inauguracao_prevista':
            $campoNormalizado = 'Data da Entrega (Previsto)';
            break;
        case 'dsc_beneficios_empreendimento':
            $campoNormalizado = 'Definição dos Benefícios do Empreendimento';
            break;
        case 'nom_conceito':
            $campoNormalizado = 'Conceito';
            break;
        case 'dsc_conceito':
            $campoNormalizado = 'Descrição do Conceito';
            break;
        case 'dsc_status_conceito':
            $campoNormalizado = 'Status do Conceito';
            break;
        case 'txt_observacao':
            $campoNormalizado = 'Observação';
            break;
        case 'dsc_aplicacao_destinacao':
            $campoNormalizado = 'Utilizado em qual aplicação?';
            break;
        case 'prc_execucao_financeira':
            $campoNormalizado = '% de Execução Financeira';
            break;
        case 'bln_empreendimento_estrategico':
            $campoNormalizado = 'Empreendimento Estratégico';
            break;

        case 'dsc_emenda_tipo':
            $campoNormalizado = 'Emenda (Tipo)';
            break;
        case 'cod_pl':
            $campoNormalizado = 'Código do Projeto de Lei';
            break;
        case 'num_rp':
            $campoNormalizado = 'Resultado Primário';
            break;
        case 'dsc_decisao_parecer':
            $campoNormalizado = 'Decisão (Parecer)';
            break;
        case 'cod_tipo_autor':
            $campoNormalizado = 'Código do Tipo de Autor';
            break;
        case 'dsc_tipo_autor':
            $campoNormalizado = 'Tipo de Autor';
            break;
        case 'cod_grupo_autor':
            $campoNormalizado = 'Código do Grupo de Autor';
            break;
        case 'dsc_grupo_autor':
            $campoNormalizado = 'Grupo de Autor';
            break;
        case 'cod_autor':
            $campoNormalizado = 'Código do Autor';
            break;
        case 'nom_autor':
            $campoNormalizado = 'Nome do Autor';
            break;
        case 'cod_emenda':
            $campoNormalizado = 'Código da Emenda';
            break;
        case 'num_emenda':
            $campoNormalizado = 'Número da Emenda';
            break;
        case 'emenda':
            $campoNormalizado = 'Emenda';
            break;
        case 'cod_unid_orca':
            $campoNormalizado = 'Unidade Orçamentária';
            break;
        case 'nom_abrev_uo':
            $campoNormalizado = 'Nome abreviado da Unidade Orçamentária';
            break;
        case 'cod_subtitulo':
            $campoNormalizado = 'Código do Subtítulo';
            break;
        case 'num_funcional':
            $campoNormalizado = 'Funcional';
            break;
        case 'nom_subtitulo_complemento':
            $campoNormalizado = 'Subtítulo complemento';
            break;
        case 'txt_titulo':
            $campoNormalizado = 'Título da Emenda';
            break;
        case 'num_id_uso':
            $campoNormalizado = 'Identificador de Uso';
            break;
        case 'expr1020':
            $campoNormalizado = 'Expressão 1020';
            break;
        case 'cod_gnd':
            $campoNormalizado = 'Grupo da Natureza de Despesa';
            break;
        case 'cod_mod_aplic':
            $campoNormalizado = 'Modalidade de Aplicação';
            break;
        case 'ind_result_primario':
            $campoNormalizado = 'Índice do Resultado Primário';
            break;
        case 'vlr_acrec':
            $campoNormalizado = 'Valor de Acréscimo';
            break;
        case 'vlr_canc':
            $campoNormalizado = 'Valor Cancelado';
            break;
        case 'vlr_saldo':
            $campoNormalizado = 'Valor de Saldo';
            break;
        case 'expr1027':
            $campoNormalizado = 'Expressão 1027';
            break;
        case 'sgl_uf_autor':
            $campoNormalizado = 'UF do Autor';
            break;
        case 'nom_partido':
            $campoNormalizado = 'Partido';
            break;
        case 'sgl_partido':
            $campoNormalizado = 'Sigla do Partido';
            break;
        case 'cod_esfera':
            $campoNormalizado = 'Código da Esfera';
            break;
        case 'dsc_tipo':
            $campoNormalizado = 'Tipo de Emenda';
            break;
        case 'expr1033':
            $campoNormalizado = 'Expressão 1033';
            break;
        case 'sgl_uf':
            $campoNormalizado = 'UF';
            break;
        case 'cod_localidade':
            $campoNormalizado = 'Código da Localidade';
            break;
        case 'dsc_categoria':
            $campoNormalizado = 'Categoria da Emenda';
            break;
        case 'cod_fonte':
            $campoNormalizado = 'Código da Fonte';
            break;
        case 'num_ano_arquivo_autografo':
            $campoNormalizado = 'Exercício Financeiro (Ano)';
            break;

        case 'cod_autor_emenda':
            $campoNormalizado = 'Código do Autor da Emenda';
            break;
        case 'cod_unidade_orcamentaria':
            $campoNormalizado = 'Unidade Orçamentária';
            break;
        case 'cod_acao':
            $campoNormalizado = 'Código da Ação';
            break;
        case 'cod_localizador':
            $campoNormalizado = 'Localizador';
            break;
        case 'cod_id_uso':
            $campoNormalizado = 'Identificador de Uso';
            break;
        case 'cod_modalidade':
            $campoNormalizado = 'Modalidade';
            break;
        case 'vlr_emenda_aprovada':
            $campoNormalizado = 'Valor da Emenda Aprovada';
            break;
        case 'vlr_indicado':
            $campoNormalizado = 'Valor Indicado';
            break;
        case 'vlr_priorizado':
            $campoNormalizado = 'Valor Priorizado';
            break;
        case 'vlr_impedido':
            $campoNormalizado = 'Valor Impedido';
            break;
        case 'vlr_autorizado':
            $campoNormalizado = 'Valor Autorizado';
            break;
        case 'vlr_disponivel':
            $campoNormalizado = 'Valor Disponível';
            break;
        case 'vlr_bloqueado_emenda':
            $campoNormalizado = 'Valor Bloqueado da Emenda';
            break;
        case 'vlr_bloqueado_sof':
            $campoNormalizado = 'Valor Bloqueado pela SOF';
            break;
        case 'vlr_bloqueado_sof_remanejamento':
            $campoNormalizado = 'Valor Bloqueado pela SOF por Remanejamento';
            break;
        case 'vlr_bloqueado_remanejamento':
            $campoNormalizado = 'Valor Bloqueado por Remanejamento';
            break;
        case 'cod_ptres':
            $campoNormalizado = 'Código PTRES';
            break;
        case 'dte_carga':
            $campoNormalizado = 'Data da Carga';
            break;
        case 'num_ano_arquivo_siop':
            $campoNormalizado = 'Exercício Financeiro (Ano)';
            break;

        case 'cod_acao_orcamentaria':
            $campoNormalizado = 'Ação Orçamentária';
            break;
        case 'cod_unidade_orcamentaria':
            $campoNormalizado = 'Unidade Orçamentária';
            break;
        case 'cod_acao_orcamentaria_cod_unidade_orcamentaria':
            $campoNormalizado = 'Ação/Unidade Orçamentária';
            break;
        case 'cod_unidade_nivel_dois':
            $campoNormalizado = 'Unidade Responsável';
            break;
        case 'txt_tipo_instrumento':
            $campoNormalizado = 'Tipo de Instrumento';
            break;
        case 'txt_item_investimento':
            $campoNormalizado = 'Item de Investimento';
            break;
        case 'bln_necessario_cadastro_programa_siconv':
            $campoNormalizado = 'Precisa de Programa no SICONV?';
            break;
        case 'rp':
            $campoNormalizado = 'Qual o RP?';
            break;
        case 'created_at':
            $campoNormalizado = 'Data de criação';
            break;
        case 'cod_programa':
            $campoNormalizado = 'Código do Programa SICONV';
            break;
        case 'nom_programa':
            $campoNormalizado = 'Descrição do Programa SICONV';
            break;
        case 'dsc_modalidade_programa':
            $campoNormalizado = 'Tipo de Instrumento Permitido no Programa SICONV';
            break;
        case 'dsc_sit_programa':
            $campoNormalizado = 'Situação do Programa SICONV';
            break;
        case 'cod_idprograma':
            $campoNormalizado = 'Identificador Único na Tabela do Programa SICONV';
            break;
        case 'cod_orgao_sup_programa':
            $campoNormalizado = 'Código do Órgão Superior do Programa do SICONV';
            break;
        case 'num_acao_orcamentaria':
            $campoNormalizado = 'Ação Orçamentária';
            break;
        case 'dte_disponibilizacao':
            $campoNormalizado = 'Data de Disponibilização do Programa';
            break;
        case 'num_ano_disponiblizacao':
            $campoNormalizado = 'Ano de Disponibilização do Programa';
            break;
        case 'dte_prog_ini_receb_prop':
            $campoNormalizado = 'Data Inicial do Recebimento de Proposta';
            break;
        case 'dte_prog_fim_receb_prop':
            $campoNormalizado = 'Data Final do Recebimento de Proposta';
            break;
        case 'dte_prog_ini_emenda_par':
            $campoNormalizado = 'Data Inicial da Emenda Parlamentar';
            break;
        case 'dte_prog_fim_emenda_par':
            $campoNormalizado = 'Data Final da Emenda Parlamentar';
            break;
        case 'dte_prog_ini_benef_esp':
            $campoNormalizado = 'Data de Início no Programa do Benefício Especial';
            break;
        case 'dte_prog_fim_benef_esp':
            $campoNormalizado = 'Data fim no Programa do Benefício Especial';
            break;
        case 'dsc_natureza_juridica_programa':
            $campoNormalizado = 'Descrição da Natureza Jurídica do Programa';
            break;
        case 'sgl_uf_programa':
            $campoNormalizado = 'UF de abrangência do Programa';
            break;
        case 'dsc_orgao_sup_programa':
            $campoNormalizado = 'Descrição do Órgão Superior do Programa do SICONV';
            break;
        case 'nom_autor_emenda':
            $campoNormalizado = 'Autor da Emenda';
            break;
        case 'cod_siop_cnpj':
            $campoNormalizado = 'ID da tabela';
            break;
        case 'cod_orgao':
            $campoNormalizado = 'Código do Órgão';
            break;
        case 'cod_id_doc':
            $campoNormalizado = 'Código do Documento';
            break;
        case 'cod_cnpj_beneficiario':
            $campoNormalizado = 'CNPJ do Beneficiário';
            break;
        case 'dsc_localizador':
            $campoNormalizado = 'Localizador';
            break;
        case 'nom_beneficiario':
            $campoNormalizado = 'Beneficiário';
            break;
        case 'dsc_grupo_natureza_despesa':
            $campoNormalizado = 'Grupo da Natureza de Despesa';
            break;
        case 'dsc_identificador_uso':
            $campoNormalizado = 'ID Uso';
            break;

        case 'cod_unidade_orcamentaria':
            $campoNormalizado = 'Código da Unidade Orçamentária';
            break;
        case 'dsc_unidade_orcamentaria':
            $campoNormalizado = 'Unidade Orçamentária';
            break;
        case 'cod_programa':
            $campoNormalizado = 'Código do Programa';
            break;
        case 'dsc_programa':
            $campoNormalizado = 'Programa';
            break;
        case 'cod_acao':
            $campoNormalizado = 'Código da Ação';
            break;
        case 'dsc_acao':
            $campoNormalizado = 'Ação';
            break;
        case 'cod_resultado_primario_lei':
            $campoNormalizado = 'Código do Resultado Primário';
            break;
        case 'dsc_resultado_primario_lei':
            $campoNormalizado = 'Resultado Primário';
            break;

        case 'cod_po_unidade_orcamentaria':
            $campoNormalizado = 'Código da Unidade Orçamentária do Plano Orçamentário';
            break;
        case 'cod_po_funcao':
            $campoNormalizado = 'Código da Função do Plano Orçamentário';
            break;
        case 'cod_po_subfuncao':
            $campoNormalizado = 'Código da Subfunção do Plano Orçamentário';
            break;
        case 'cod_po_programa':
            $campoNormalizado = 'Código do Programa do Plano Orçamentário';
            break;
        case 'cod_po_acao':
            $campoNormalizado = 'Código da Ação do Plano Orçamentário';
            break;
        case 'cod_po':
            $campoNormalizado = 'Código do Plano Orçamentário';
            break;
        case 'dsc_plano_orcamentario':
            $campoNormalizado = 'Plano Orçamentário';
            break;
        case 'cod_plano_trabalho':
            $campoNormalizado = 'Código do Plano de Trabalho';
            break;
        case 'dsc_plano_trabalho':
            $campoNormalizado = 'Plano de Trabalho';
            break;
        case 'cod_plano_trabalho_resumido':
            $campoNormalizado = 'Código do Plano de Trabalho Resumido';
            break;
        case 'cod_fonte_sof':
            $campoNormalizado = 'Código da Fonte SOF';
            break;
        case 'cod_natureza_despesa':
            $campoNormalizado = 'Código da Natureza de Despesa';
            break;
        case 'dsc_natureza_despesa':
            $campoNormalizado = 'Natureza de Despesa';
            break;
        case 'sgl_uf_plano_trabalho':
            $campoNormalizado = 'Sigla da UF do Plano de Trabalho';
            break;
        case 'dsc_uf_plano_trabalho':
            $campoNormalizado = 'UF do Plano de Trabalho';
            break;
        case 'cod_autor_emendas_orcamento':
            $campoNormalizado = 'Código do Autor da Emenda';
            break;
        case 'dsc_autor_emendas_orcamento':
            $campoNormalizado = 'Autor da Emenda';
            break;
        case 'vlr_dotacao_inicial':
            $campoNormalizado = 'Valor da Dotação Inicial';
            break;
        case 'vlr_dotacao_atualizada':
            $campoNormalizado = 'Valor da Dotação Atualizada';
            break;
        case 'vlr_dotacao_cancelada_e_remanejada':
            $campoNormalizado = 'Valor da Dotação Cancelada e Remanejada';
            break;
        case 'vlr_credito_disponivel':
            $campoNormalizado = 'Valor do Crédito Disponível';
            break;
        case 'vlr_credito_indisponivel':
            $campoNormalizado = 'Valor do Crédito Indisponível';
            break;

        case 'sgl_ministerio':
            $campoNormalizado = 'Ministério';
            break;
        case 'dsc_tipo_iniciativa':
            $campoNormalizado = 'Tipo de Entrega';
            break;
        case 'dsc_iniciativa':
            $campoNormalizado = 'Descrição da Iniciativa';
            break;
        case 'dte_conclusao_realizada':
            $campoNormalizado = 'Data de Conclusão efetiva';
            break;
        case 'txt_comentario':
            $campoNormalizado = 'Comentários';
            break;
        case 'bln_validado_secretario':
            $campoNormalizado = 'Validação do(a) Secretário(a) Nacional';
            break;
        case 'nom_secretario':
            $campoNormalizado = 'Nome do(a) Secretário(a) Nacional que validou a Entrega';
            break;
        case 'mes':
            $campoNormalizado = 'Mês';
            break;
        case 'ano':
            $campoNormalizado = 'Ano';
            break;
        case 'txt_entregas_mais_relevantes':
            $campoNormalizado = 'Entregas mais Relevantes';
            break;
        case 'organizacaoid':
            $campoNormalizado = 'Unidade da Organização';
            break;
        case 'bln_validado_ascom':
            $campoNormalizado = 'Autorização AESCOM';
            break;
        case 'nom_ascom':
            $campoNormalizado = 'Nome do(a) Responsável na AESCOM que validou a Entrega';
            break;
        case 'dte_conclusao_realizada':
            $campoNormalizado = 'Data de Conclusão';
            break;
        case 'dte_conclusao_prevista':
            $campoNormalizado = 'Data de Conclusão prevista';
            break;
        case 'bln_multissetorial':
            $campoNormalizado = 'Multissetorial';
            break;
        case 'dsc_orgao_participante':
            $campoNormalizado = 'Órgãos Participantes';
            break;
        case 'dsc_eixo':
            $campoNormalizado = 'Eixo';
            break;
        case 'dsc_tema':
            $campoNormalizado = 'Tema';
            break;
        case 'dsc_projeto':
            $campoNormalizado = 'Entrega';
            break;
        case 'dte_sugerida_evento_entrega':
            $campoNormalizado = 'Data e Hora Sugerida para o Evento de Entrega';
            break;
        case 'dsc_tipo':
            $campoNormalizado = 'Tipo';
            break;
        case 'dsc_status':
            $campoNormalizado = 'Status';
            break;
        case 'dsc_abrangencia':
            $campoNormalizado = 'Abragência';
            break;
        case 'dsc_nivel_interesse':
            $campoNormalizado = 'Nível de Interesse';
            break;
        case 'dsc_destaque':
            $campoNormalizado = 'Destaque';
            break;
        case 'txt_anexos':
            $campoNormalizado = 'Anexos';
            break;
        case 'num_instrumento':
            $campoNormalizado = 'Número do Instrumento';
            break;
        case 'sgl_uf':
            $campoNormalizado = 'UF';
            break;
        case 'nom_municipio':
            $campoNormalizado = 'Município';
            break;
        case 'vlr_investimento':
            $campoNormalizado = 'Valor do Investimento';
            break;
        case 'vlr_repasse':
            $campoNormalizado = 'Valor de Repasse (Governo Federal)';
            break;
        case 'prc_executado':
            $campoNormalizado = 'Percentual de Execução Financeira';
            break;
        case 'vlr_total_desbloqueado':
            $campoNormalizado = 'Valor Total Desbloqueado';
            break;
        case 'txt_historico_funcional_obra':
            $campoNormalizado = 'A obra já estava funcionando? Se sim, desde quando? Já tinha pessoas sendo atendidas?';
            break;
        case 'num_ano_contrato':
            $campoNormalizado = 'Ano de Início do Contrato';
            break;
        case 'num_ano_inicio_obra':
            $campoNormalizado = 'Ano de Início da Obra';
            break;
        case 'txt_historico_obra':
            $campoNormalizado = 'Histórico. A obra teve problemas? Houve paralisação? Favor detalhar';
            break;
        case 'dsc_tipo_entrega_empreendimento':
            $campoNormalizado = 'O empreendimento é dividido em fases? Metas? Essa é uma entrega total ou parcial? Favor detalhar';
            break;
        case 'bln_emenda_parlamentar':
            $campoNormalizado = 'Emenda Parlamentar?';
            break;
        case 'nom_parlamentar':
            $campoNormalizado = 'Nome do(a) Parlamentar';
            break;
        case 'vlr_repassado_2019':
            $campoNormalizado = 'Valor repassado em 2019';
            break;
        case 'vlr_repassado_2020':
            $campoNormalizado = 'Valor repassado em 2020';
            break;
        case 'vlr_repassado_2021':
            $campoNormalizado = 'Valor repassado em 2021';
            break;
        case 'num_quantidade_municipio':
            $campoNormalizado = 'Quantidade de Município(s)';
            break;
        case 'bln_tipo_beneficiados':
            $campoNormalizado = 'Tipo de Beneficiados';
            break;

        case 'nom_entrega':
            $campoNormalizado = 'Nome da Entrega';
            break;
        case 'dsc_tipo_entrega':
            $campoNormalizado = 'Tipo da Entrega';
            break;
        case 'dsc_entrega':
            $campoNormalizado = 'Descrição da Entrega';
            break;
        case 'vlr_repassado_2023':
            $campoNormalizado = 'Valor Repassado até 2023';
            break;
        case 'prc_executado_financeiro':
            $campoNormalizado = 'Percentual de Execução Financeira';
            break;
        case 'num_ano_inicio_contrato':
            $campoNormalizado = 'Ano de Início do Contrato';
            break;
        case 'dte_previsao_entrega':
            $campoNormalizado = 'Data de Previsão de Entrega';
            break;
        case 'dte_previsao_evento':
            $campoNormalizado = 'Data de Sugestão do Evento de Entrega';
            break;
        case 'num_mes':
            $campoNormalizado = 'Mês da Entrega';
            break;
        case 'num_ano':
            $campoNormalizado = 'Ano';
            break;
        case 'num_trimestre':
            $campoNormalizado = 'Trimestre da Entrega';
            break;
        case 'sgl_unidade':
            $campoNormalizado = 'Unidade';
            break;
        case 'nom_parlamentar':
            $campoNormalizado = 'Nome do Parlamentar';
            break;
        case 'dsc_tipo_fundo':
            $campoNormalizado = 'Tipo';
            break;
        case 'dsc_linha_financiamento':
            $campoNormalizado = 'Linha de Financiamento';
            break;
        case 'dsc_finalidade_operacao':
            $campoNormalizado = 'Finalidade da Operação';
            break;
        case 'nom_empreendimento':
            $campoNormalizado = 'Nome do Empreendimento';
            break;
        case 'nom_empreendimento_divulgacao':
            $campoNormalizado = 'Nome para divulgação';
            break;
        case 'dsc_subeixo':
            $campoNormalizado = 'Subeixo';
            break;
        case 'nom_ministerio':
            $campoNormalizado = 'Ministério';
            break;
        case 'dte_inicio_empreendimento':
            $campoNormalizado = 'Início do Empreendimento';
            break;
        case 'dte_previsao_conclusao_empreendimento':
            $campoNormalizado = 'Previsão de Conclusão do Empreendimento';
            break;
        case 'vlr_a_executar':
            $campoNormalizado = 'Valor a Executar';
            break;
        case 'txt_descricao':
            $campoNormalizado = 'Descrição';
            break;
        case 'sgl_area_responsavel':
            $campoNormalizado = 'Sigla da Área Responsável';
            break;
        case 'dsc_modalidade_site':
            $campoNormalizado = 'Modalidade Site';
            break;
        case 'vlr_investimento_planejado_pos_2026':
            $campoNormalizado = 'Valor de Investimento Planejado Pós 2026';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;

        default:
            $campoNormalizado = $campo;
            break;
    }

    return $campoNormalizado;
}

function nomeCampoTabNovoPacNormalizado($campo)
{

    $campoNormalizado = '';

    switch ($campo) {
        case 'NPAC_DBGESTORES':
            $campoNormalizado = 'Contratos de Repasse';
            break;
        case 'NPAC_FGTS':
            $campoNormalizado = 'FGTS';
            break;
        case 'NPAC_SICONV':
            $campoNormalizado = 'Convênios';
            break;
        case 'PAC_MCID':
            $campoNormalizado = 'PAC Min. Cidades';
            break;
        case 'PAC_MI':
            $campoNormalizado = 'PAC Min. Integração';
            break;
        case 'origem':
            $campoNormalizado = 'Origem dos Instrumentos';
            break;
        case 'txt_sigla_area':
            $campoNormalizado = 'Área(s) do MDR';
            break;
        case 'Contratado - concluído':
            $campoNormalizado = 'Concluído';
            break;
        case 'Contratado - normal':
            $campoNormalizado = 'Normal';
            break;
        case 'Contratado - suspensiva':
            $campoNormalizado = 'Suspensiva';
            break;
        case 'Contratado - em Prestação de Contas':
            $campoNormalizado = 'Em Prestação de Contas';
            break;
        case 'Contratado - em TCE':
            $campoNormalizado = 'Em TCE';
            break;
        case 'Contratado - liminar':
            $campoNormalizado = 'Liminar';
            break;
        case 'Contratado – suspensiva e liminar':
            $campoNormalizado = 'Suspensiva e Liminar';
            break;
        case 'periodoInicial':
            $campoNormalizado = 'Período';
            break;
        case 'dsc_situacao_contrato_mdr':
            $campoNormalizado = 'Situação do Instrumento MDR';
            break;
        case 'uf':
            $campoNormalizado = 'UF';
            break;
        case 'cod_mdr':
            $campoNormalizado = 'Código MIDR';
            break;
        case 'cod_cipi':
            $campoNormalizado = 'Código CIPI <small class="text-muted">(Cadastro Integrado de Projetos de Investimento)</small>';
            break;
        case 'num_processo_sei':
            $campoNormalizado = 'Número do Processo SEI';
            break;
        case 'cod_s2id':
            $campoNormalizado = 'Código do Sistema S2iD';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case 'numero_generico_contrato':
            $campoNormalizado = 'Outro Número do Instrumento';
            break;
        case 'municipio':
            $campoNormalizado = 'Município';
            break;
        case 'ibge':
            $campoNormalizado = 'Código IBGE';
            break;
        case 'codigo_saci':
            $campoNormalizado = 'Código SACI';
            break;
        case 'nm_convenio_siafi':
            $campoNormalizado = 'Número Convênio SIAFI';
            break;
        case 'tipo_instrumento':
            $campoNormalizado = 'Tipo do Instrumento';
            break;
        case 'dsc_agente_financeiro':
            $campoNormalizado = 'Agente Financeiro';
            break;
        case 'fonte':
            $campoNormalizado = 'Fonte';
            break;
        case 'concedente':
            $campoNormalizado = 'Concedente';
            break;
        case 'bln_ativo':
            $campoNormalizado = 'Instrumento Ativo';
            break;
        case 'dsc_modalidade':
            $campoNormalizado = 'Modalidade';
            break;
        case 'dsc_situacao_contrato':
            $campoNormalizado = 'Situação do Contrato';
            break;
        case 'dsc_situacao_obra':
            $campoNormalizado = 'Situação da Obra';
            break;
        case 'txt_empreendimento':
            $campoNormalizado = 'Nome do Empreendimento';
            break;
        case 'prc_execucao':
            $campoNormalizado = 'Percentual de Execução Financeira';
            break;
        case 'prc_execucao_fisica':
            $campoNormalizado = 'Percentual de Execução Física';
            break;
        case 'prc_empenhado':
            $campoNormalizado = 'Percentual de Empenhado';
            break;
        case 'prc_desembolsado':
            $campoNormalizado = 'Percentual de Desembolso';
            break;
        case 'prc_desbloqueado':
            $campoNormalizado = 'Percentual de Desbloqueio';
            break;
        case 'vlr_investimento':
            $campoNormalizado = 'Valor de Investimento';
            break;
        case 'vlr_repasse':
            $campoNormalizado = 'Valor de Repasse';
            break;
        case 'vlr_contrapartida':
            $campoNormalizado = 'Valor Contrapartida';
            break;
        case 'vlr_pago_conta':
            $campoNormalizado = 'Valor Desembolsado/Liberado';
            break;
        case 'vlr_desbloqueado_vr':
            $campoNormalizado = 'Valor Desbloqueado';
            break;
        case 'funcional':
            $campoNormalizado = 'Funcional';
            break;
        case 'dsc_tomador':
            $campoNormalizado = 'Tomador';
            break;
        case 'bln_carteira_mdr':
            $campoNormalizado = 'Carteira MDR';
            break;
        case 'bln_carteira_mdr_ativo':
            $campoNormalizado = 'Carteira MDR Ativa';
            break;
        case 'dsc_situacao_objeto_mdr':
            $campoNormalizado = 'Situação do Objeto MDR';
            break;
        case 'vlr_empenhado':
            $campoNormalizado = 'Valor Empenhado';
            break;
        case 'dte_carga':
            $campoNormalizado = 'Data de Atualização da Carga';
            break;
        case 'cod_contrato':
            $campoNormalizado = 'Número do Instrumento';
            break;
        case 'cod_pt':
            $campoNormalizado = 'Código do Plano de Trabalho';
            break;
        case 'cod_ag_operador':
            $campoNormalizado = 'Cód. Agente Operador';
            break;
        case 'cod_id_proposta':
            $campoNormalizado = 'ID Proposta';
            break;
        case 'cod_nr_proposta':
            $campoNormalizado = 'Número da Proposta';
            break;
        case 'siconv_instrumento_ativo':
            $campoNormalizado = 'SICONV Instrumento Ativo';
            break;
        case 'siconv_convenio_assinado':
            $campoNormalizado = 'Convênio Assinado';
            break;
        case 'dte_assinatura_contrato':
            $campoNormalizado = 'Data Assinatura Contrato';
            break;
        case 'dte_inicio_obra_efetiva':
            $campoNormalizado = 'Data de Início (Realizado)';
            break;
        case 'dte_fim_obra':
            $campoNormalizado = 'Data do Fim (Realizado)';
            break;
        case 'dte_ult_mov_fin':
            $campoNormalizado = 'Data da Última Movimentação Financeira';
            break;
        case 'dsc_situacao_contrato_compl':
            $campoNormalizado = 'Sit. do Contrato Complemento';
            break;
        case 'siconv_dsc_sit_convenio':
            $campoNormalizado = 'SICONV Situação do Convênio';
            break;
        case 'siconv_dsc_sit_contratacao':
            $campoNormalizado = 'SICONV Situação da Contratação';
            break;
        case 'siconv_dsc_subsit_convenio':
            $campoNormalizado = 'SICONV Subsituação do Convênio';
            break;
        case 'siconv_dsc_sit_proposta':
            $campoNormalizado = 'SICONV Situação da Proposta';
            break;
        case 'dsc_motivo_paralisacao':
            $campoNormalizado = 'Motivo da Paralisação';
            break;
        case 'par_348':
            $campoNormalizado = 'Portaria 348';
            break;
        case 'avancar_cidades':
            $campoNormalizado = 'Avançar Cidades';
            break;
        case 'bln_emenda':
            $campoNormalizado = 'É emenda';
            break;
        case 'vlr_pago':
            $campoNormalizado = 'Valor Pago';
            break;
        case 'dsc_paralisada_mdr':
            $campoNormalizado = 'Paralisada MDR';
            break;
        case 'bln_pro_brasil':
            $campoNormalizado = 'Pró Brasil';
            break;
        case 'bln_mais_nordeste':
            $campoNormalizado = 'Mais Nordeste';
            break;
        case 'bln_revitalizacao_bacias':
            $campoNormalizado = 'Revitalização de Bacias';
            break;
        case 'qtd_dias_ult_mov_fin':
            $campoNormalizado = 'Quantidade de dias desde a última movimentação financeira';
            break;
        case 'e_pac':
            $campoNormalizado = 'Fez parte do PAC';
            break;
        case 'num_cnpj_tomador':
            $campoNormalizado = 'CNPJ do tomador';
            break;
        case 'dte_inicio_obra_prevista':
            $campoNormalizado = 'Data de Início (Previsto)';
            break;
        case 'dte_fim_obra_prevista':
            $campoNormalizado = 'Data do Fim (Previsto)';
            break;
        case 'dte_ano_conclusao_previsto':
            $campoNormalizado = 'Ano Previsto de Cnclusão';
            break;
        case 'vlr_restos_a_pagar':
            $campoNormalizado = 'Valor de Resto a Pagar';
            break;
        case 'vlr_anual_conclusao_previsto':
            $campoNormalizado = 'Valor para Conclusão Anual (Conforme CIPI)';
            break;
        case 'bln_apto_inauguracao':
            $campoNormalizado = 'Entrega Confirmada?';
            break;
        case 'dte_inauguracao':
            $campoNormalizado = 'Data da Entrega (Realizado)';
            break;
        case 'dsc_familias_beneficiadas':
            $campoNormalizado = 'Descrição das Famílias Beneficiadas';
            break;
        case 'num_familias_beneficiadas':
            $campoNormalizado = 'Número das Famílias Beneficiadas';
            break;
        case 'trimestre':
            $campoNormalizado = 'Trimestre';
            break;
        case 'mes_previsto':
            $campoNormalizado = 'Mês Previsto da Entrega';
            break;
        case 'mes':
            $campoNormalizado = 'Mês Efetivo da Entrega';
            break;
        case 'tipo_objeto':
            $campoNormalizado = 'Classificação por Eixo, Tipo e Subtipo';
            break;
        case 'ano_previsto':
            $campoNormalizado = 'Ano Previsto da Entrega';
            break;
        case 'ano':
            $campoNormalizado = 'Ano Efetivo da Entrega';
            break;
        case 'dte_inauguracao_prevista':
            $campoNormalizado = 'Data da Entrega (Previsto)';
            break;
        case 'dsc_beneficios_empreendimento':
            $campoNormalizado = 'Definição dos Benefícios do Empreendimento';
            break;
        case 'nom_conceito':
            $campoNormalizado = 'Conceito';
            break;
        case 'dsc_conceito':
            $campoNormalizado = 'Descrição do Conceito';
            break;
        case 'dsc_status_conceito':
            $campoNormalizado = 'Status do Conceito';
            break;
        case 'txt_observacao':
            $campoNormalizado = 'Observação';
            break;
        case 'dsc_aplicacao_destinacao':
            $campoNormalizado = 'Utilizado em qual aplicação?';
            break;
        case 'prc_execucao_financeira':
            $campoNormalizado = '% de Execução Financeira';
            break;
        case 'bln_empreendimento_estrategico':
            $campoNormalizado = 'Empreendimento Estratégico';
            break;

        case 'dsc_emenda_tipo':
            $campoNormalizado = 'Emenda (Tipo)';
            break;
        case 'cod_pl':
            $campoNormalizado = 'Código do Projeto de Lei';
            break;
        case 'num_rp':
            $campoNormalizado = 'Resultado Primário';
            break;
        case 'dsc_decisao_parecer':
            $campoNormalizado = 'Decisão (Parecer)';
            break;
        case 'cod_tipo_autor':
            $campoNormalizado = 'Código do Tipo de Autor';
            break;
        case 'dsc_tipo_autor':
            $campoNormalizado = 'Tipo de Autor';
            break;
        case 'cod_grupo_autor':
            $campoNormalizado = 'Código do Grupo de Autor';
            break;
        case 'dsc_grupo_autor':
            $campoNormalizado = 'Grupo de Autor';
            break;
        case 'cod_autor':
            $campoNormalizado = 'Código do Autor';
            break;
        case 'nom_autor':
            $campoNormalizado = 'Nome do Autor';
            break;
        case 'cod_emenda':
            $campoNormalizado = 'Código da Emenda';
            break;
        case 'num_emenda':
            $campoNormalizado = 'Número da Emenda';
            break;
        case 'emenda':
            $campoNormalizado = 'Emenda';
            break;
        case 'cod_unid_orca':
            $campoNormalizado = 'Unidade Orçamentária';
            break;
        case 'nom_abrev_uo':
            $campoNormalizado = 'Nome abreviado da Unidade Orçamentária';
            break;
        case 'cod_subtitulo':
            $campoNormalizado = 'Código do Subtítulo';
            break;
        case 'num_funcional':
            $campoNormalizado = 'Funcional';
            break;
        case 'nom_subtitulo_complemento':
            $campoNormalizado = 'Subtítulo complemento';
            break;
        case 'txt_titulo':
            $campoNormalizado = 'Título da Emenda';
            break;
        case 'num_id_uso':
            $campoNormalizado = 'Identificador de Uso';
            break;
        case 'expr1020':
            $campoNormalizado = 'Expressão 1020';
            break;
        case 'cod_gnd':
            $campoNormalizado = 'Grupo da Natureza de Despesa';
            break;
        case 'cod_mod_aplic':
            $campoNormalizado = 'Modalidade de Aplicação';
            break;
        case 'ind_result_primario':
            $campoNormalizado = 'Índice do Resultado Primário';
            break;
        case 'vlr_acrec':
            $campoNormalizado = 'Valor de Acréscimo';
            break;
        case 'vlr_canc':
            $campoNormalizado = 'Valor Cancelado';
            break;
        case 'vlr_saldo':
            $campoNormalizado = 'Valor de Saldo';
            break;
        case 'expr1027':
            $campoNormalizado = 'Expressão 1027';
            break;
        case 'sgl_uf_autor':
            $campoNormalizado = 'UF do Autor';
            break;
        case 'nom_partido':
            $campoNormalizado = 'Partido';
            break;
        case 'sgl_partido':
            $campoNormalizado = 'Sigla do Partido';
            break;
        case 'cod_esfera':
            $campoNormalizado = 'Código da Esfera';
            break;
        case 'dsc_tipo':
            $campoNormalizado = 'Tipo de Emenda';
            break;
        case 'expr1033':
            $campoNormalizado = 'Expressão 1033';
            break;
        case 'sgl_uf':
            $campoNormalizado = 'UF';
            break;
        case 'cod_localidade':
            $campoNormalizado = 'Código da Localidade';
            break;
        case 'dsc_categoria':
            $campoNormalizado = 'Categoria da Emenda';
            break;
        case 'cod_fonte':
            $campoNormalizado = 'Código da Fonte';
            break;
        case 'num_ano_arquivo_autografo':
            $campoNormalizado = 'Exercício Financeiro (Ano)';
            break;

        case 'cod_autor_emenda':
            $campoNormalizado = 'Código do Autor da Emenda';
            break;
        case 'cod_unidade_orcamentaria':
            $campoNormalizado = 'Unidade Orçamentária';
            break;
        case 'cod_acao':
            $campoNormalizado = 'Código da Ação';
            break;
        case 'cod_localizador':
            $campoNormalizado = 'Localizador';
            break;
        case 'cod_id_uso':
            $campoNormalizado = 'Identificador de Uso';
            break;
        case 'cod_modalidade':
            $campoNormalizado = 'Modalidade';
            break;
        case 'vlr_emenda_aprovada':
            $campoNormalizado = 'Valor da Emenda Aprovada';
            break;
        case 'vlr_indicado':
            $campoNormalizado = 'Valor Indicado';
            break;
        case 'vlr_priorizado':
            $campoNormalizado = 'Valor Priorizado';
            break;
        case 'vlr_impedido':
            $campoNormalizado = 'Valor Impedido';
            break;
        case 'vlr_autorizado':
            $campoNormalizado = 'Valor Autorizado';
            break;
        case 'vlr_disponivel':
            $campoNormalizado = 'Valor Disponível';
            break;
        case 'vlr_bloqueado_emenda':
            $campoNormalizado = 'Valor Bloqueado da Emenda';
            break;
        case 'vlr_bloqueado_sof':
            $campoNormalizado = 'Valor Bloqueado pela SOF';
            break;
        case 'vlr_bloqueado_sof_remanejamento':
            $campoNormalizado = 'Valor Bloqueado pela SOF por Remanejamento';
            break;
        case 'vlr_bloqueado_remanejamento':
            $campoNormalizado = 'Valor Bloqueado por Remanejamento';
            break;
        case 'cod_ptres':
            $campoNormalizado = 'Código PTRES';
            break;
        case 'dte_carga':
            $campoNormalizado = 'Data da Carga';
            break;
        case 'num_ano_arquivo_siop':
            $campoNormalizado = 'Exercício Financeiro (Ano)';
            break;

        case 'cod_acao_orcamentaria':
            $campoNormalizado = 'Código da Ação Orçamentária (Letra e número - 4 dígitos)';
            break;
        case 'cod_unidade_orcamentaria':
            $campoNormalizado = 'Unidade Orçamentária';
            break;
        case 'cod_acao_orcamentaria_cod_unidade_orcamentaria':
            $campoNormalizado = 'Ação/Unidade Orçamentária';
            break;
        case 'cod_unidade_nivel_dois':
            $campoNormalizado = 'Unidade Responsável';
            break;
        case 'txt_tipo_instrumento':
            $campoNormalizado = 'Tipo de Instrumento';
            break;
        case 'txt_item_investimento':
            $campoNormalizado = 'Item de Investimento';
            break;
        case 'bln_necessario_cadastro_programa_siconv':
            $campoNormalizado = 'Precisa de Programa no SICONV?';
            break;
        case 'rp':
            $campoNormalizado = 'Qual o RP?';
            break;
        case 'created_at':
            $campoNormalizado = 'Data de criação';
            break;
        case 'cod_programa':
            $campoNormalizado = 'Código do Programa SICONV';
            break;
        case 'nom_programa':
            $campoNormalizado = 'Descrição do Programa SICONV';
            break;
        case 'dsc_modalidade_programa':
            $campoNormalizado = 'Tipo de Instrumento Permitido no Programa SICONV';
            break;
        case 'dsc_sit_programa':
            $campoNormalizado = 'Situação do Programa SICONV';
            break;
        case 'cod_idprograma':
            $campoNormalizado = 'Identificador Único na Tabela do Programa SICONV';
            break;
        case 'cod_orgao_sup_programa':
            $campoNormalizado = 'Código do Órgão Superior do Programa do SICONV';
            break;
        case 'num_acao_orcamentaria':
            $campoNormalizado = 'Ação Orçamentária';
            break;
        case 'dte_disponibilizacao':
            $campoNormalizado = 'Data de Disponibilização do Programa';
            break;
        case 'num_ano_disponiblizacao':
            $campoNormalizado = 'Ano de Disponibilização do Programa';
            break;
        case 'dte_prog_ini_receb_prop':
            $campoNormalizado = 'Data Inicial do Recebimento de Proposta';
            break;
        case 'dte_prog_fim_receb_prop':
            $campoNormalizado = 'Data Final do Recebimento de Proposta';
            break;
        case 'dte_prog_ini_emenda_par':
            $campoNormalizado = 'Data Inicial da Emenda Parlamentar';
            break;
        case 'dte_prog_fim_emenda_par':
            $campoNormalizado = 'Data Final da Emenda Parlamentar';
            break;
        case 'dte_prog_ini_benef_esp':
            $campoNormalizado = 'Data de Início no Programa do Benefício Especial';
            break;
        case 'dte_prog_fim_benef_esp':
            $campoNormalizado = 'Data fim no Programa do Benefício Especial';
            break;
        case 'dsc_natureza_juridica_programa':
            $campoNormalizado = 'Descrição da Natureza Jurídica do Programa';
            break;
        case 'sgl_uf_programa':
            $campoNormalizado = 'UF de abrangência do Programa';
            break;
        case 'dsc_orgao_sup_programa':
            $campoNormalizado = 'Descrição do Órgão Superior do Programa do SICONV';
            break;
        case 'nom_autor_emenda':
            $campoNormalizado = 'Autor da Emenda';
            break;
        case 'cod_siop_cnpj':
            $campoNormalizado = 'ID da tabela';
            break;
        case 'cod_orgao':
            $campoNormalizado = 'Código do Órgão';
            break;
        case 'cod_id_doc':
            $campoNormalizado = 'Código do Documento';
            break;
        case 'cod_cnpj_beneficiario':
            $campoNormalizado = 'CNPJ do Beneficiário';
            break;
        case 'dsc_localizador':
            $campoNormalizado = 'Localizador';
            break;
        case 'nom_beneficiario':
            $campoNormalizado = 'Beneficiário';
            break;
        case 'dsc_grupo_natureza_despesa':
            $campoNormalizado = 'Grupo da Natureza de Despesa';
            break;
        case 'dsc_identificador_uso':
            $campoNormalizado = 'ID Uso';
            break;

        case 'cod_unidade_orcamentaria':
            $campoNormalizado = 'Código da Unidade Orçamentária';
            break;
        case 'dsc_unidade_orcamentaria':
            $campoNormalizado = 'Unidade Orçamentária';
            break;
        case 'cod_programa':
            $campoNormalizado = 'Código do Programa';
            break;
        case 'dsc_programa':
            $campoNormalizado = 'Programa';
            break;
        case 'cod_acao':
            $campoNormalizado = 'Código da Ação';
            break;
        case 'dsc_acao':
            $campoNormalizado = 'Ação';
            break;
        case 'cod_resultado_primario_lei':
            $campoNormalizado = 'Código do Resultado Primário';
            break;
        case 'dsc_resultado_primario_lei':
            $campoNormalizado = 'Resultado Primário';
            break;

        case 'cod_po_unidade_orcamentaria':
            $campoNormalizado = 'Código da Unidade Orçamentária do Plano Orçamentário';
            break;
        case 'cod_po_funcao':
            $campoNormalizado = 'Código da Função do Plano Orçamentário';
            break;
        case 'cod_po_subfuncao':
            $campoNormalizado = 'Código da Subfunção do Plano Orçamentário';
            break;
        case 'cod_po_programa':
            $campoNormalizado = 'Código do Programa do Plano Orçamentário';
            break;
        case 'cod_po_acao':
            $campoNormalizado = 'Código da Ação do Plano Orçamentário';
            break;
        case 'cod_po':
            $campoNormalizado = 'Código do Plano Orçamentário';
            break;
        case 'dsc_plano_orcamentario':
            $campoNormalizado = 'Plano Orçamentário';
            break;
        case 'cod_plano_trabalho':
            $campoNormalizado = 'Código do Plano de Trabalho';
            break;
        case 'dsc_plano_trabalho':
            $campoNormalizado = 'Plano de Trabalho';
            break;
        case 'cod_plano_trabalho_resumido':
            $campoNormalizado = 'Código do Plano de Trabalho Resumido';
            break;
        case 'cod_fonte_sof':
            $campoNormalizado = 'Código da Fonte SOF';
            break;
        case 'cod_natureza_despesa':
            $campoNormalizado = 'Código da Natureza de Despesa';
            break;
        case 'dsc_natureza_despesa':
            $campoNormalizado = 'Natureza de Despesa';
            break;
        case 'sgl_uf_plano_trabalho':
            $campoNormalizado = 'Sigla da UF do Plano de Trabalho';
            break;
        case 'dsc_uf_plano_trabalho':
            $campoNormalizado = 'UF do Plano de Trabalho';
            break;
        case 'cod_autor_emendas_orcamento':
            $campoNormalizado = 'Código do Autor da Emenda';
            break;
        case 'dsc_autor_emendas_orcamento':
            $campoNormalizado = 'Autor da Emenda';
            break;
        case 'vlr_dotacao_inicial':
            $campoNormalizado = 'Valor da Dotação Inicial';
            break;
        case 'vlr_dotacao_atualizada':
            $campoNormalizado = 'Valor da Dotação Atualizada';
            break;
        case 'vlr_dotacao_cancelada_e_remanejada':
            $campoNormalizado = 'Valor da Dotação Cancelada e Remanejada';
            break;
        case 'vlr_credito_disponivel':
            $campoNormalizado = 'Valor do Crédito Disponível';
            break;
        case 'vlr_credito_indisponivel':
            $campoNormalizado = 'Valor do Crédito Indisponível';
            break;

        case 'sgl_ministerio':
            $campoNormalizado = 'Ministério';
            break;
        case 'dsc_tipo_iniciativa':
            $campoNormalizado = 'Tipo de Entrega';
            break;
        case 'dsc_iniciativa':
            $campoNormalizado = 'Descrição da Iniciativa';
            break;
        case 'dte_conclusao_realizada':
            $campoNormalizado = 'Data de Conclusão efetiva';
            break;
        case 'txt_comentario':
            $campoNormalizado = 'Comentários';
            break;
        case 'bln_validado_secretario':
            $campoNormalizado = 'Validação do(a) Secretário(a) Nacional';
            break;
        case 'nom_secretario':
            $campoNormalizado = 'Nome do(a) Secretário(a) Nacional que validou a Entrega';
            break;
        case 'mes':
            $campoNormalizado = 'Mês';
            break;
        case 'ano':
            $campoNormalizado = 'Ano';
            break;
        case 'txt_entregas_mais_relevantes':
            $campoNormalizado = 'Entregas mais Relevantes';
            break;
        case 'organizacaoid':
            $campoNormalizado = 'Unidade da Organização';
            break;
        case 'bln_validado_ascom':
            $campoNormalizado = 'Autorização AESCOM';
            break;
        case 'nom_ascom':
            $campoNormalizado = 'Nome do(a) Responsável na AESCOM que validou a Entrega';
            break;
        case 'dte_conclusao_realizada':
            $campoNormalizado = 'Data de Conclusão';
            break;
        case 'dte_conclusao_prevista':
            $campoNormalizado = 'Data de Conclusão prevista';
            break;
        case 'bln_multissetorial':
            $campoNormalizado = 'Multissetorial';
            break;
        case 'dsc_orgao_participante':
            $campoNormalizado = 'Órgãos Participantes';
            break;
        case 'dsc_eixo':
            $campoNormalizado = 'Eixo';
            break;
        case 'dsc_tema':
            $campoNormalizado = 'Tema';
            break;
        case 'dsc_projeto':
            $campoNormalizado = 'Entrega';
            break;
        case 'dte_sugerida_evento_entrega':
            $campoNormalizado = 'Data e Hora Sugerida para o Evento de Entrega';
            break;
        case 'dsc_tipo':
            $campoNormalizado = 'Tipo';
            break;
        case 'dsc_status':
            $campoNormalizado = 'Status';
            break;
        case 'dsc_abrangencia':
            $campoNormalizado = 'Abragência';
            break;
        case 'dsc_nivel_interesse':
            $campoNormalizado = 'Nível de Interesse';
            break;
        case 'dsc_destaque':
            $campoNormalizado = 'Destaque';
            break;
        case 'txt_anexos':
            $campoNormalizado = 'Anexos';
            break;
        case 'num_instrumento':
            $campoNormalizado = 'Número do Instrumento';
            break;
        case 'sgl_uf':
            $campoNormalizado = 'UF';
            break;
        case 'nom_municipio':
            $campoNormalizado = 'Município';
            break;
        case 'vlr_investimento':
            $campoNormalizado = 'Valor do Investimento';
            break;
        case 'vlr_repasse':
            $campoNormalizado = 'Valor de Repasse (Governo Federal)';
            break;
        case 'prc_executado':
            $campoNormalizado = 'Percentual de Execução Financeira';
            break;
        case 'vlr_total_desbloqueado':
            $campoNormalizado = 'Valor Total Desbloqueado';
            break;
        case 'txt_historico_funcional_obra':
            $campoNormalizado = 'A obra já estava funcionando? Se sim, desde quando? Já tinha pessoas sendo atendidas?';
            break;
        case 'num_ano_contrato':
            $campoNormalizado = 'Ano de Início do Contrato';
            break;
        case 'num_ano_inicio_obra':
            $campoNormalizado = 'Ano de Início da Obra';
            break;
        case 'txt_historico_obra':
            $campoNormalizado = 'Histórico. A obra teve problemas? Houve paralisação? Favor detalhar';
            break;
        case 'dsc_tipo_entrega_empreendimento':
            $campoNormalizado = 'O empreendimento é dividido em fases? Metas? Essa é uma entrega total ou parcial? Favor detalhar';
            break;
        case 'bln_emenda_parlamentar':
            $campoNormalizado = 'Emenda Parlamentar?';
            break;
        case 'nom_parlamentar':
            $campoNormalizado = 'Nome do(a) Parlamentar';
            break;
        case 'vlr_repassado_2019':
            $campoNormalizado = 'Valor repassado em 2019';
            break;
        case 'vlr_repassado_2020':
            $campoNormalizado = 'Valor repassado em 2020';
            break;
        case 'vlr_repassado_2021':
            $campoNormalizado = 'Valor repassado em 2021';
            break;
        case 'num_quantidade_municipio':
            $campoNormalizado = 'Quantidade de Município(s)';
            break;
        case 'bln_tipo_beneficiados':
            $campoNormalizado = 'Tipo de Beneficiados';
            break;

        case 'nom_entrega':
            $campoNormalizado = 'Nome da Entrega';
            break;
        case 'dsc_tipo_entrega':
            $campoNormalizado = 'Tipo da Entrega';
            break;
        case 'dsc_entrega':
            $campoNormalizado = 'Descrição da Entrega';
            break;
        case 'vlr_repassado_2023':
            $campoNormalizado = 'Valor Repassado até 2023';
            break;
        case 'prc_executado_financeiro':
            $campoNormalizado = 'Percentual de Execução Financeira';
            break;
        case 'num_ano_inicio_contrato':
            $campoNormalizado = 'Ano de Início do Contrato';
            break;
        case 'dte_previsao_entrega':
            $campoNormalizado = 'Data de Previsão de Entrega';
            break;
        case 'dte_previsao_evento':
            $campoNormalizado = 'Data de Sugestão do Evento de Entrega';
            break;
        case 'num_mes':
            $campoNormalizado = 'Mês da Entrega';
            break;
        case 'num_ano':
            $campoNormalizado = 'Ano';
            break;
        case 'num_trimestre':
            $campoNormalizado = 'Trimestre da Entrega';
            break;
        case 'sgl_unidade':
            $campoNormalizado = 'Unidade';
            break;
        case 'nom_parlamentar':
            $campoNormalizado = 'Nome do Parlamentar';
            break;
        case 'dsc_tipo_fundo':
            $campoNormalizado = 'Tipo';
            break;
        case 'dsc_linha_financiamento':
            $campoNormalizado = 'Linha de Financiamento';
            break;
        case 'dsc_finalidade_operacao':
            $campoNormalizado = 'Finalidade da Operação';
            break;
        case 'nom_empreendimento':
            $campoNormalizado = 'Nome do Empreendimento';
            break;
        case 'nom_empreendimento_divulgacao':
            $campoNormalizado = 'Nome para divulgação';
            break;
        case 'dsc_subeixo':
            $campoNormalizado = 'Subeixo';
            break;
        case 'nom_ministerio':
            $campoNormalizado = 'Ministério';
            break;
        case 'dte_inicio_empreendimento':
            $campoNormalizado = 'Início do Empreendimento';
            break;
        case 'dte_previsao_conclusao_empreendimento':
            $campoNormalizado = 'Previsão de Conclusão do Empreendimento';
            break;
        case 'cod_plano_orcamentario':
            $campoNormalizado = 'Plano Orçamentário -PO (Letra e número - 4 dígitos)';
            break;
        case 'bln_emblematico':
            $campoNormalizado = 'Emblemático';
            break;
        case 'dsc_natureza_empreendimento_ajustado':
            $campoNormalizado = 'Natureza do Empreendimento - Ajustado';
            break;
        case 'vlr_ogu_empenhado_loa_2023':
            $campoNormalizado = 'OGU - Valor Empenhado da LOA 2023 (R$)';
            break;
        case 'dsc_situacao':
            $campoNormalizado = 'Situação';
            break;
        case 'dsc_fase':
            $campoNormalizado = 'Fase';
            break;
        case 'bln_paralisado':
            $campoNormalizado = 'Empreendimento paralisado?';
            break;
        case 'txt_motivo_paralisacao':
            $campoNormalizado = 'Motivo da Paralisação';
            break;
        case 'txt_proxima_entrega_planejada':
            $campoNormalizado = 'Próxima entrega planejada';
            break;
        case 'dte_proxima_entrega_planejada':
            $campoNormalizado = 'Data da próxima entrega planejada';
            break;
        case 'vlr_ogu_pago_repassado_total_loa_mais_rap':
            $campoNormalizado = 'OGU - Valor Pago / Repassado total (LOA+RAP) (R$)';
            break;
        case 'vlr_fin_pago_desbloqueado':
            $campoNormalizado = 'FIN - Valor Pago / Desbloqueado (R$)';
            break;
        case 'vlr_priv_pago':
            $campoNormalizado = 'PRIV - Valor pago (R$)';
            break;
        case 'vlr_estatal_pago':
            $campoNormalizado = 'Estatal - Valor pago (R$)';
            break;
        case 'vlr_fundos_setorias_pago':
            $campoNormalizado = 'Fundos Setoriais - Valor pago (R$)';
            break;
        case 'bln_em_obras_com_data_inicio_futuro':
            $campoNormalizado = 'Em obras com data de início no futuro';
            break;
        case 'bln_obras_com_fase_nao_iniciado':
            $campoNormalizado = 'Em obras com fase Não iniciado';
            break;
        case 'bln_nao_iniciado_com_percentual_de_execucao':
            $campoNormalizado = 'Não iniciado com % de execução';
            break;
        case 'bln_nao_iniciado_com_fases_em_andamento':
            $campoNormalizado = 'Não iniciado com fases em andamento';
            break;
        case 'bln_em_obras_com_percentual_execucao_igual_100_porcento':
            $campoNormalizado = 'Em obras com % de execução = 100%';
            break;
        case 'bln_concluido_com_percentual_de_execucao_menor_que_100_porcento':
            $campoNormalizado = 'Concluído com % de execução menor que 100%';
            break;
        case 'bln_posterior_a_conclusao_ou_identicos':
            $campoNormalizado = 'Início posterior a Conclusão ou idênticos';
            break;
        case 'txt_justificativa_manutencao_inconsistencias_identificadas':
            $campoNormalizado = 'Justificativas para manutenção das inconsistências identificadas';
            break;
        case 'updated_at':
            $campoNormalizado = 'Data de atualização';
            break;
        case 'vlr_a_executar':
            $campoNormalizado = 'Valor a Executar';
            break;
        case 'txt_descricao':
            $campoNormalizado = 'Descrição';
            break;
        case 'sgl_area_responsavel':
            $campoNormalizado = 'Sigla da Área Responsável';
            break;
        case 'dsc_modalidade_site':
            $campoNormalizado = 'Modalidade Site';
            break;
        case 'vlr_investimento_planejado_pos_2026':
            $campoNormalizado = 'Valor de Investimento Planejado Pós 2026';
            break;
        case 'vlr_investimento_planejado_2023_a_2026':
            $campoNormalizado = 'Valor de Investimento Planejado 2023 - 2026';
            break;
        case 'cod_sistema_de_referencia':
            $campoNormalizado = 'Código do Sistema de Referência';
            break;
        case 'nom_executor':
            $campoNormalizado = 'Executor';
            break;
        case 'num_meta_fisica':
            $campoNormalizado = 'Meta Física';
            break;
        case 'dsc_unidade_de_medida':
            $campoNormalizado = 'Unidade de Medida';
            break;
        case 'num_latitude':
            $campoNormalizado = 'Latitude';
            break;
        case 'num_longitude':
            $campoNormalizado = 'Longitude';
            break;
        case 'nom_sistema_de_referencia':
            $campoNormalizado = 'Sistema de Referência';
            break;
        case 'vlr_fin_pago_desbloqueado_2024':
            $campoNormalizado = 'FIN - Valor Pago / Desbloqueado em 2024';
            break;
        case 'vlr_pago_fundos_setoriais_2024':
            $campoNormalizado = 'Fundos Setoriais - Valor pago em 2024';
            break;
        case 'vlr_pago_estatal_2024':
            $campoNormalizado = 'Estatal - Valor pago em 2024';
            break;
        case 'txt_resultado':
            $campoNormalizado = 'Resultados';
            break;
        case 'txt_restricao':
            $campoNormalizado = 'Restrições';
            break;
        case 'txt_providencia':
            $campoNormalizado = 'Providências';
            break;
        case 'codigoUnidade':
            $campoNormalizado = 'Área Responsável';
            break;
        case 'vlr_ogu_empenhado_loa_2024':
            $campoNormalizado = 'OGU - Valor Empenhado da LOA 2024';
            break;
        case 'vlr_ogu_pago_repassado_loa_2024':
            $campoNormalizado = 'OGU - Valor Pago / Repassado (LOA) 2024';
            break;
        case 'vlr_pago_rap_2024':
            $campoNormalizado = 'OGU - Valor Pago RAP 2024';
            break;
        case 'vlr_priv_pago_2024':
            $campoNormalizado = 'PRIV - Valor pago em 2024';
            break;
        case 'vlr_financeiro':
            $campoNormalizado = 'Necessidade Financeira';
            break;
        case 'vlr_orcamentario':
            $campoNormalizado = 'Necessidade Orçamentária';
            break;
        case 'txt_observacao_financeira':
            $campoNormalizado = 'Observação';
            break;
        case 'txt_observacao_orcamentario':
            $campoNormalizado = 'Observação';
            break;
        case 'txt_observacao_credito_disponivel':
            $campoNormalizado = 'Observação';
            break;
        case 'vlr_saldo_empenhado':
            $campoNormalizado = 'Valor de Saldo Empenhado';
            break;
        case 'txt_observacao_saldo_empenhado':
            $campoNormalizado = 'Observação';
            break;
        case 'vlr_suplementacao_orcamentaria':
            $campoNormalizado = 'Valor de Suplementação Orçamentária Necessária';
            break;
        case 'txt_observacao_suplementacao_orcamentaria':
            $campoNormalizado = 'Observação';
            break;
        case 'cod_pac':
            $campoNormalizado = 'Código PAC ou interno';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;

        default:
            $campoNormalizado = $campo;
            break;
    }

    return $campoNormalizado;
}

function nomeCampoUsersNormalizado($campo)
{

    $campoNormalizado = '';

    switch ($campo) {
        case 'name':
            $campoNormalizado = 'Nome';
            break;
        case 'email':
            $campoNormalizado = 'E-mail';
            break;
        case 'codigoUnidade':
            $campoNormalizado = 'Lotação de exercício (SIORG)';
            break;
        case 'cod_perfil':
            $campoNormalizado = 'Perfil de acesso';
            break;
        case 'ativo':
            $campoNormalizado = 'Cadastro Ativo ou Inativo';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;
        case '':
            $campoNormalizado = '';
            break;

        default:
            $campoNormalizado = $campo;
            break;
    }

    return $campoNormalizado;
}

function calcularPercentual($valorObtido = 0, $valorTotal = 0)
{

    $resultado = formatarValorFloatMysql(0.00);

    if ($valorTotal != 0) {

        $resultado = formatarValorFloatMysql((($valorObtido / $valorTotal) * 100));
    }

    return $resultado;
}

function to_array($value): array
{
    $arr = (array) $value;
    if (!is_object($value)) {
        return $arr;
    }
    $class = get_class($value);
    $keys = str_replace(["\0*\0", "\0{$class}\0"], '', array_keys($arr));
    return array_combine($keys, $arr);
}

function acresentarZeroADireita($valor = '')
{

    $quantidade = strlen($valor);

    $quantidade == 1 ? $valor = '00000' . $valor : $valor = $valor;

    $quantidade == 2 ? $valor = '0000' . $valor : $valor = $valor;

    $quantidade == 3 ? $valor = '000' . $valor : $valor = $valor;

    $quantidade == 4 ? $valor = '00' . $valor : $valor = $valor;

    $quantidade == 5 ? $valor = '0' . $valor : $valor = $valor;

    // $quantidade == 6 ? $valor = '0'.$valor : $valor = $valor;

    return $valor;
}

function contains($palavra, $frase)
{
    return strpos($frase, $palavra) !== false;
}

function formatCnpjCpf($value)
{
    $cnpj_cpf = preg_replace("/\D/", '', $value);

    if (strlen($cnpj_cpf) === 11) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    }

    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}

function naoMostrarCpfCompleto($value)
{

    $cnpj_cpf = preg_replace("/\D/", '', $value);

    if (strlen($cnpj_cpf) === 11) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{1})/", "\$1.***.***-*\$5", $cnpj_cpf);
    }

    return preg_replace("/(\d{1})(\d{3})(\d{3})(\d{4})(\d{1})/", "\$1.***.***-*\$6", $cnpj_cpf);
}

function pergarPrimeiraLetraUser($nameUser = "")
{

    $partesNome = explode(" ", $nameUser);

    is_array($partesNome) && $partesNome > 1 ? $sigla = mb_substr($partesNome[0], 0, 1, 'UTF-8') . mb_substr($partesNome[count($partesNome) - 1], 0, 1, 'UTF-8') : $sigla = mb_substr($partesNome[count($partesNome) - 1], 0, 1, 'UTF-8');

    return $sigla;
}

function mascaraNumProcessoSei($num_processo_sei = '')
{

    if (isset($num_processo_sei) && !is_null($num_processo_sei) && $num_processo_sei != '') {

        $len = strlen($num_processo_sei);

        if ($len === 17) {
            $parte_01 = '';
            $parte_02 = '';
            $parte_03 = '';
            $parte_04 = '';

            $parte_01 = mb_substr($num_processo_sei, 0, 5, 'UTF-8');
            $parte_02 = mb_substr($num_processo_sei, 5, 6, 'UTF-8');
            $parte_03 = mb_substr($num_processo_sei, 11, 4, 'UTF-8');
            $parte_04 = mb_substr($num_processo_sei, 15, 2, 'UTF-8');

            if ($parte_01 != '' && $parte_02 != '' && $parte_03 != '' && $parte_04 != '') {
                $num_processo_sei = $parte_01 . '.' . $parte_02 . '/' . $parte_03 . '-' . $parte_04;
            } else {
                $num_processo_sei = '-';
            }
        }

        if ($len === 15) {
            $parte_01 = '';
            $parte_02 = '';
            $parte_03 = '';
            $parte_04 = '';

            $parte_01 = mb_substr($num_processo_sei, 0, 5, 'UTF-8');
            $parte_02 = mb_substr($num_processo_sei, 5, 6, 'UTF-8');
            $parte_03 = mb_substr($num_processo_sei, 11, 2, 'UTF-8');
            $parte_04 = mb_substr($num_processo_sei, 13, 2, 'UTF-8');

            if ($parte_01 != '' && $parte_02 != '' && $parte_03 != '' && $parte_04 != '') {
                $num_processo_sei = $parte_01 . '.' . $parte_02 . '/' . $parte_03 . '-' . $parte_04;
            } else {
                $num_processo_sei = '-';
            }
        }
    }

    return $num_processo_sei;
}

function limpezaTexto($texto = "")
{

    // $texto = htmlentities($texto, null, 'utf-8');
    $texto = str_replace("&nbsp;", " ", $texto);
    $texto = str_replace("\n", "", $texto);
    $texto = str_replace("\r", "", $texto);
    $texto = preg_replace('/\s/', ' ', $texto);
    $texto = str_replace("  ", " ", $texto);

    return $texto;
}

function prettify_numbers($number = '0', $decimals = 2, $int_only = false)
{
    $number = (string) $number;

    $simbol = null;

    // yotta: 1000000000000000000000000
    if ($number > '99999999999999999999999') {
        $number = bcdiv($number, '1000000000000000000000000', $decimals);
        $simbol = 'Y';
    }

    // Zetta: 1000000000000000000000
    elseif ($number > '999999999999999999999') {
        $number = bcdiv($number, '1000000000000000000000', $decimals);
        $simbol = 'Z';
    }

    // Exa : 1000000000000000000
    elseif ($number > '999999999999999999') {
        $number = bcdiv($number, '1000000000000000000', $decimals);
        $simbol = 'E';
    }

    // Peta : 1000000000000000
    elseif ($number > '999999999999999') {
        $number = bcdiv($number, '1000000000000000', $decimals);
        $simbol = 'P';
    }

    // Tera : 1000000000000
    elseif ($number > '999999999999') {
        $number = bcdiv($number, '1000000000000', $decimals);
        $simbol = 'T';
    }

    // Tera : 1000000000
    elseif ($number > '999999999') {
        $number = bcdiv($number, '1000000000', $decimals);

        $primeiroNumero = explode('.', $number);

        if (is_array($primeiroNumero)) {

            $primeiroNumero[0] > 1 ? $simbol = ' Bilhões' : $simbol = ' Bilhão';
        }
    }

    // Mega : 1000000
    elseif ($number > '999999') {
        $number = bcdiv($number, '1000000', $decimals);

        $primeiroNumero = explode('.', $number);

        if (is_array($primeiroNumero)) {

            $primeiroNumero[0] > 1 ? $simbol = ' Milhões' : $simbol = ' Milhão';
        }
    }

    // Kilo : 1000
    elseif ($number > '999') {
        $number = bcdiv($number, '1000', $decimals);
        $simbol = ' Mil';
    }

    // Retorna apenas o número inteiro
    if ($int_only)
        return (int) $number . $simbol;

    if ($simbol === ' Milhões' || $simbol === ' Milhão') {

        $number = str_replace('.', ',', $number);
    }

    // Retorna o número e o símbolo
    return $number . $simbol;
}

function verificarTipoRetornoApi($url = null)
{

    if (isset($url) && !is_null($url) && $url != '') {

        $response = file_get_contents($url);

        // Obtém os cabeçalhos da resposta
        $headers = get_headers($url, 1);

        // Verifica se o cabeçalho Content-Type está presente
        if (array_key_exists('Content-Type', $headers)) {
            $contentType = $headers['Content-Type'];
            if (strpos($contentType, 'xml') !== false) {
                // É um retorno XML
                return "XML";
            } elseif (strpos($contentType, 'json') !== false) {
                // É um retorno JSON
                return "JSON";
            } else {
                // Tipo de retorno desconhecido
                return "desconhecido";
            }
        } else {
            // O cabeçalho Content-Type não está presente
            return "desconhecido";
        }
    } else {
        return null;
    }
}

function transformarNomeTabelaParaNomeModel($nomeTabela = null)
{
    $nomeTabela = str_replace('_', ' ', $nomeTabela); // Substitui os underscores por espaços
    $nomeTabela = ucwords($nomeTabela); // Converte as primeiras letras de cada palavra para maiúsculas
    $nomeTabela = str_replace(' ', '', $nomeTabela); // Remove os espaços

    return $nomeTabela;
}

function isJson($string)
{
    if (!is_string($string)) {
        return false;
    }

    json_decode($string);
    return true;
}

function retornaTextoTirandoParteDoTexto($texto = null, $textoParaTirar = null)
{
    if (isset($texto) && !is_null($texto) && $texto != '') {

        if (isset($textoParaTirar) && !is_null($textoParaTirar) && $textoParaTirar != '') {

            $novoTexto = str_replace($textoParaTirar, "", $texto);

            return $novoTexto;
        } else {
            return $texto;
        }
    } else {
        return null;
    }
}

function retornaTextoTrocandoParteDoTexto($texto = null)
{
    if (isset($texto) && !is_null($texto) && $texto != '') {

        // matriz de entrada
        $what = array('em ', 'há ');

        // matriz de saída
        $by = array('vencerá em ', 'venceu há');

        // devolver a string
        return str_replace($what, $by, $texto);
    } else {
        return $texto;
    }
}

function primeiraLetraMaiuscula($string, $firstAlwaysUpper = true, $encoding = "UTF-8")
{
    $lc = ["aos", "e", "o", "os", "as", "a", "do", "dos", "das", "da", "ante", "após", "até", "com", "contra", "de", "desde", "em", "entre", "para", "perante", "por", "sem", "sob", "sobre", "trás", "que", "seu", "sua", "seus", "suas", "MDS"];

    $a = explode(" ", $string);
    $r = "";

    foreach ($a as $i => $word) {
        if (!$firstAlwaysUpper) {
            $r .= ((strlen($word) <= 3) || in_array(mb_convert_case($word, MB_CASE_LOWER, $encoding), $lc))
                ? mb_convert_case($word, MB_CASE_LOWER, $encoding) . ' '
                : mb_convert_case($word, MB_CASE_TITLE, $encoding) . ' ';
        } else {
            if ($i === 0) {
                $r .= mb_convert_case($word, MB_CASE_TITLE, $encoding) . ' ';
            } else {
                $r .= ((strlen($word) <= 3) || in_array(mb_convert_case($word, MB_CASE_LOWER, $encoding), $lc))
                    ? mb_convert_case($word, MB_CASE_LOWER, $encoding) . ' '
                    : mb_convert_case($word, MB_CASE_TITLE, $encoding) . ' ';
            }
        }
    }

    return trim($r);
}

function passarTextoParaMaiusculo($texto = '')
{

    return mb_strtoupper($texto, 'UTF-8');
}

function passarTextoParaMinusculo($texto = '')
{

    return mb_strtolower($texto, 'UTF-8');
}

function retornarPrimeiroUltimoNome($nome)
{


    $nomeSeparados = explode(' ', $nome);

    return $nomeSeparados[0] . ' ' . $nomeSeparados[count($nomeSeparados) - 1];
}

function applyMask($value, $mask)
{
    $maskedValue = '';
    $valueIdx = 0;

    for ($i = 0; $i < strlen($mask); $i++) {
        if ($valueIdx >= strlen($value)) {
            break;
        }

        if ($mask[$i] === '#') {
            $maskedValue .= $value[$valueIdx];
            $valueIdx++;
        } else {
            $maskedValue .= $mask[$i];
        }
    }

    return $maskedValue;
}

function getVariableType($variable)
{
    if (is_string($variable)) {
        return 'string';
    } elseif (is_int($variable)) {
        return 'integer';
    } elseif (is_float($variable)) {
        return 'float';
    } elseif (is_bool($variable)) {
        return 'boolean';
    } elseif (is_array($variable)) {
        return 'array';
    } elseif (is_object($variable)) {
        return 'object';
    } elseif (is_null($variable)) {
        return 'null';
    } else {
        return 'unknown';
    }
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function validateDoublePrecision($value)
{
    // Verifica se o valor é um número e se é do tipo float
    return is_numeric($value) && is_float((float) $value);
}

function isUUID($str)
{
    // Define o padrão de expressão regular para um UUID
    $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

    // Use a função preg_match para verificar se a string corresponde ao padrão UUID
    return preg_match($pattern, $str) === 1;
}

function iconRotasIntegracaoNacional($nomRota = null)
{

    if (isset($nomRota) && !is_null($nomRota) && $nomRota != '') {

        switch ($nomRota) {
            case 'AÇAÍ':
                $icon = ['acai.png', 'http://portalrotas.avaliacao.org.br/rota/rota-do-acai/1'];
                break;
            case 'BIODIVERSIDADE':
                $icon = ['biodiversidade.png', 'http://portalrotas.avaliacao.org.br/rota/rota-da-biodiversidade/2'];
                break;
            case 'CACAU':
                $icon = ['cacau.png', 'http://portalrotas.avaliacao.org.br/rota/rota-do-cacau/3'];
                break;
            case 'CORDEIRO':
                $icon = ['cordeiro.png', 'http://portalrotas.avaliacao.org.br/rota/rota-do-cordeiro/4'];
                break;
            case 'ECONOMIA CIRCULAR':
                $icon = ['economia_circular.png', 'http://portalrotas.avaliacao.org.br/rota/rota-da-economia-circular/5'];
                break;
            case 'FRUTICULTURA':
                $icon = ['fruticultura.png', 'http://portalrotas.avaliacao.org.br/rota/rota-da-fruticultura/6'];
                break;
            case 'LEITE':
                $icon = ['leite.png', 'http://portalrotas.avaliacao.org.br/rota/rota-do-leite/7'];
                break;
            case 'MEL':
                $icon = ['mel.png', 'http://portalrotas.avaliacao.org.br/rota/rota-do-mel/12'];
                break;
            case 'TIC':
                $icon = ['tic.png', 'http://portalrotas.avaliacao.org.br/rota/rota-da-tic/9'];
                break;
            case 'MODA':
                $icon = ['moda.png', 'http://portalrotas.avaliacao.org.br/rota/rota-da-moda/30'];
                break;
            case 'PESCADO':
                $icon = ['pescado.png', 'http://portalrotas.avaliacao.org.br/rota/rota-do-pescado/8'];
                break;
            case '':
                $icon = ['', ''];
                break;

            default:
                $icon = ['', ''];
                break;
        }
    }

    return $icon;
}

function transformarJsonParaArray($objeto = null)
{
    if ($objeto instanceof JsonSerializable) {
        $jsonString = json_encode($objeto);
        $objetoTransformado = json_decode($jsonString, true);
        return $objetoTransformado;
    } else {
        $objetoTransformado = json_decode(json_encode($objeto), true);
        return $objetoTransformado;
    }
}

function converterImagemParaBase64($caminhoImagem = null)
{
    $path = $caminhoImagem;
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    return $base64;
}

function verificaPalavra($string, $palavra)
{
    // Verifica se a palavra existe na string
    if (strpos($string, $palavra) !== false) {
        return true;
    } else {
        return false;
    }
}

function isBigInt($number)
{
    // Verifica se o número é uma string que representa um inteiro
    if (is_string($number) && preg_match('/^-?\d+$/', $number)) {
        // Verifica se o número está fora do alcance do tipo integer no PostgreSQL
        return $number > '2147483647' || $number < '-2147483648';
    }
    return false;
}

function tirarEspacosEntrePalavrasEPassarParaMinusculo($string)
{
    // Remove os espaços entre as palavras
    $stringFormatada = preg_replace('/\s+/', '', $string);

    // Converte a string para minúsculas
    $stringFormatada = strtolower($stringFormatada);

    return $stringFormatada;
}

function generateUUID()
{
    // Gerar 16 bytes (128 bits) de dados aleatórios
    $data = random_bytes(16);

    // Ajustar os bits conforme especificado na RFC 4122, seção 4.4
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // versão 4
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // variante 10xx

    // Formatar os dados como uma string UUID
    return sprintf(
        '%08s-%04s-%04s-%04s-%12s',
        bin2hex(substr($data, 0, 4)),
        bin2hex(substr($data, 4, 2)),
        bin2hex(substr($data, 6, 2)),
        bin2hex(substr($data, 8, 2)),
        bin2hex(substr($data, 10, 6))
    );
}

// Função para normalizar o texto
function normalizeText($text)
{
    // Normalize quebras de linha
    $text = str_replace(["\r\n", "\r"], "\n", $text);

    // Remova espaços em branco no início e no fim
    $text = trim($text);

    // Substitua múltiplos espaços consecutivos por um único espaço
    $text = preg_replace('/\s+/', ' ', $text);

    // Remova outros caracteres invisíveis, se necessário (exemplo: caractere de tabulação)
    $text = str_replace(["\t"], ' ', $text);

    // Retorne o texto normalizado
    return $text;
}

function substituirPipePorHifen($string)
{
    // A função str_replace substitui todas as ocorrências do caractere "|" por "-"
    return str_replace('|', '-', $string);
}

function alterarDescricaoLideranca($descricaoTipoLideranca = null)
{
    switch ($descricaoTipoLideranca) {
        case 'Líder do Congresso Nacional':
            $descricaoTipoLideranca = 'Líder ';
            break;

        case 'Partido Político':
            $descricaoTipoLideranca = 'partido ';
            break;

        default:
            $descricaoTipoLideranca = $descricaoTipoLideranca;
            break;

    }

    return $descricaoTipoLideranca;
}
