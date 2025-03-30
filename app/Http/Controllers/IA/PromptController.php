<?php

namespace App\Http\Controllers\IA;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use OpenAI\Laravel\Facades\OpenAI;

class PromptController extends Controller
{
    public function getResumoExecutivoParlamentar($nomeParlamentar = null, $cargoParlamentar = null, $sglPartido = null, $sglUfRepresentacao = null)
    {
        if (!empty($nomeParlamentar) && !empty($cargoParlamentar) && !empty($sglPartido) && !empty($sglUfRepresentacao)) {

            $cacheKey = "resumo_executivo_{$cargoParlamentar}_{$nomeParlamentar}_{$sglUfRepresentacao}";

            // Cache::forget($cacheKey);

            return Cache::remember($cacheKey, now()->addHours(2), function () use ($nomeParlamentar, $cargoParlamentar, $sglPartido, $sglUfRepresentacao) {
                $response = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Você é um analista político especializado em fornecer resumos estratégicos dinâmicos para parlamentares. Cada tema deve trazer novas abordagens diariamente para evitar repetições. Os dados devem ser precisos, com fontes oficiais do ano corrente (2025) ou, se indisponíveis, do ano anterior (2024), e impacto direto para o parlamentar.'],
                        [
                            'role' => 'user',
                            'content' => "**Resumo Executivo**  \n\n"
                                . $this->gerarResumoDinamico($sglUfRepresentacao) . "**Pesquisa e análise realizada por IA especializada em dados políticos.**"
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 1000,
                    'top_p' => 1,
                    'frequency_penalty' => 0.2,
                    'presence_penalty' => 0.5
                ]);

                return $response->choices[0]->message->content ?? 'Sem resposta da OpenAI, favor aguardar um novo processamento que ocorre às 07:00 e às 13:00 diariamente.';
            });
        }

        return 'Informações insuficientes para gerar o resumo.';
    }

    private function gerarResumoDinamico($uf)
    {
        $anoAtual = date('Y'); // 2025
        $anoAnterior = date('Y') - 1; // 2024

        $temas = [
            'Economia & Empregos' => [
                'Crescimento do setor industrial' => "Fonte: IBGE - Pesquisa Industrial Mensal (PIM-PF) {$anoAtual}",
                'Desafios do agronegócio' => "Fonte: MAPA - Balanço Anual da Agropecuária {$anoAtual}",
                'Impacto do turismo na geração de empregos' => "Fonte: MTur - Anuário Estatístico do Turismo {$anoAtual}",
                'Comércio exterior e exportações' => "Fonte: MDIC - Balança Comercial Brasileira {$anoAtual}",
                'Mercado de trabalho e desemprego' => "Fonte: IBGE - PNAD Contínua {$anoAtual}",
                'Inflação e poder de compra' => "Fonte: IBGE - Índice Nacional de Preços ao Consumidor (IPCA) {$anoAtual}",
                'Desenvolvimento da economia criativa' => "Fonte: FIRJAN - Mapeamento da Economia Criativa {$anoAtual}",
                'Pequenas e médias empresas' => "Fonte: SEBRAE - Panorama dos Pequenos Negócios {$anoAtual}"
            ],
            'Infraestrutura & Obras' => [
                'Expansão de rodovias e ferrovias' => "Fonte: MT - Relatório de Infraestrutura de Transportes {$anoAtual}",
                'Situação de aeroportos e portos' => "Fonte: ANAC - Relatório Anual de Aviação Civil {$anoAtual}",
                'Projetos de saneamento e abastecimento' => "Fonte: MCid - Sistema Nacional de Informações sobre Saneamento (SNIS) {$anoAtual}",
                'Transporte público urbano' => "Fonte: MCid - Relatório Nacional de Mobilidade Urbana {$anoAtual}",
                'Telecomunicações e conectividade' => "Fonte: ANATEL - Relatório Anual do Setor de Telecomunicações {$anoAtual}",
                'Infraestrutura energética' => "Fonte: MME - Balanço Energético Nacional {$anoAtual}",
                'Obras paralisadas e retomadas' => "Fonte: TCU - Relatório de Fiscalização de Obras Públicas {$anoAtual}",
                'Investimentos em mobilidade urbana' => "Fonte: MCid - Programa Pró-Transporte {$anoAtual}"
            ],
            'Educação & Saúde' => [
                'Financiamento do ensino superior' => "Fonte: MEC - Relatório de Execução Orçamentária {$anoAtual}",
                'Déficit de médicos no interior' => "Fonte: CFM - Demografia Médica no Brasil {$anoAtual}",
                'Qualidade do ensino básico' => "Fonte: INEP - Índice de Desenvolvimento da Educação Básica (IDEB) {$anoAtual}",
                'Educação profissional e tecnológica' => "Fonte: MEC - Censo da Educação Profissional {$anoAtual}",
                'Evasão escolar' => "Fonte: IBGE - PNAD Educação {$anoAtual}",
                'Saúde mental e políticas públicas' => "Fonte: MS - Boletim Epidemiológico de Saúde Mental {$anoAtual}",
                'Tecnologia na educação' => "Fonte: Cetic.br - Pesquisa TIC Educação {$anoAtual}",
                'Vacinação e imunização' => "Fonte: MS - Dados do Programa Nacional de Imunizações (PNI) {$anoAtual}"
            ],
            'Segurança Pública' => [
                'Combate ao tráfico de drogas' => "Fonte: MJSP - Relatório Anual da Polícia Federal {$anoAtual}",
                'Violência contra mulheres' => "Fonte: FBSP - Anuário Brasileiro de Segurança Pública {$anoAtual}",
                'Condições do sistema prisional' => "Fonte: DEPEN - Levantamento Nacional de Informações Penitenciárias {$anoAtual}",
                'Crimes cibernéticos' => "Fonte: MJSP - Relatório Nacional de Crimes Cibernéticos {$anoAtual}",
                'Segurança nas fronteiras' => "Fonte: MJSP - Relatório de Operações de Fronteira {$anoAtual}",
                'Violência policial' => "Fonte: MDH - Relatório de Direitos Humanos {$anoAtual}",
                'Criminalidade juvenil' => "Fonte: MJSP - Relatório do SINASE {$anoAtual}",
                'Investimentos em inteligência' => "Fonte: MJSP - Relatório da SENASP {$anoAtual}"
            ],
            'Desenvolvimento Social' => [
                'Combate à pobreza e desigualdade' => "Fonte: IBGE - Síntese de Indicadores Sociais {$anoAtual}",
                'Programas de transferência de renda' => "Fonte: MDS - Relatório do Bolsa Família {$anoAtual}",
                'Segurança alimentar e nutricional' => "Fonte: CONSEA - Relatório de Segurança Alimentar {$anoAtual}",
                'Políticas de habitação popular' => "Fonte: MCid - Relatório do Programa Minha Casa, Minha Vida {$anoAtual}",
                'Inclusão de pessoas com deficiência' => "Fonte: IBGE - Censo Demográfico (Indicadores de Deficiência) {$anoAtual}",
                'Envelhecimento populacional' => "Fonte: IBGE - Projeções Populacionais {$anoAtual}",
                'Políticas para juventude' => "Fonte: SNJ - Relatório da Secretaria Nacional da Juventude {$anoAtual}",
                'Equidade racial' => "Fonte: IPEA - Retrato das Desigualdades de Gênero e Raça {$anoAtual}"
            ]
        ];

        // Função auxiliar para verificar disponibilidade de dados (simulação)
        $verificarDisponibilidade = function ($fonte) use ($anoAtual, $anoAnterior) {
            // Simulação: substitua por consulta real a APIs ou bancos de dados oficiais
            $dadoDisponivel2025 = rand(0, 1); // 0 = não disponível, 1 = disponível
            return $dadoDisponivel2025 ? $fonte : str_replace($anoAtual, $anoAnterior, $fonte);
        };

        $resumo = "";
        $contador = 1;
        foreach ($temas as $tema => $subtemas) {
            $subtema = array_rand($subtemas);
            $fonteAjustada = $verificarDisponibilidade($subtemas[$subtema]);
            $resumo .= "{$contador}️⃣ **{$tema}**  \n";
            $resumo .= "- **{$subtema}** no estado de {$uf}. {$fonteAjustada}  \n";
            $resumo .= "🔹 **Oportunidade:** [Informação específica].  \n";
            $resumo .= "🎯 **Ação Sugerida:** [Medida concreta].  \n\n";
            $contador++;
        }

        return $resumo;
    }
}
