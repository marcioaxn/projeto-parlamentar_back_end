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

            Cache::forget($cacheKey);

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

        $anoAtual = date('Y');
        $anoAnterior = date('Y') - 1;

        $temas = [
            'Economia & Empregos' => [
                'Crescimento do setor industrial' => "Fonte: IBGE - Relatório de Produção Industrial {$anoAtual}",
                'Desafios do agronegócio' => "Fonte: MAPA - Ministério da Agricultura {$anoAtual}",
                'Impacto do turismo na geração de empregos' => "Fonte: MTur - Boletim Econômico do Turismo {$anoAtual}",
                'Comércio exterior e exportações' => "Fonte: MDIC - Balança Comercial Brasileira {$anoAtual}",
                'Mercado de trabalho e desemprego' => "Fonte: IBGE - Pesquisa Nacional por Amostra de Domicílios Contínua {$anoAtual}",
                'Inflação e poder de compra' => "Fonte: BCB - Relatório de Inflação {$anoAtual}",
                'Desenvolvimento da economia criativa' => "Fonte: MinC - Mapeamento da Indústria Criativa {$anoAtual}",
                'Pequenas e médias empresas' => "Fonte: SEBRAE - Panorama dos Pequenos Negócios {$anoAtual}"
            ],
            'Infraestrutura & Obras' => [
                'Expansão de rodovias e ferrovias' => "Fonte: Ministério da Infraestrutura - Relatório de Obras {$anoAtual}",
                'Situação de aeroportos e portos' => "Fonte: ANAC e ANTAQ - Dados de Operação {$anoAtual}",
                'Projetos de saneamento e abastecimento' => "Fonte: SNIS - Diagnóstico do Saneamento {$anoAtual}",
                'Transporte público urbano' => "Fonte: NTU - Anuário do Transporte Público {$anoAtual}",
                'Telecomunicações e conectividade' => "Fonte: ANATEL - Relatório de Acompanhamento do Setor {$anoAtual}",
                'Infraestrutura energética' => "Fonte: MME - Boletim de Monitoramento do Sistema Elétrico {$anoAtual}",
                'Obras paralisadas e retomadas' => "Fonte: TCU - Fiscalização de Obras Públicas {$anoAtual}",
                'Investimentos em mobilidade urbana' => "Fonte: MDR - Indicadores de Mobilidade {$anoAtual}"
            ],
            'Educação & Saúde' => [
                'Financiamento do ensino superior' => "Fonte: MEC - Orçamento da Educação {$anoAtual}",
                'Déficit de médicos no interior' => "Fonte: Ministério da Saúde - Relatório de Recursos Humanos {$anoAtual}",
                'Qualidade do ensino básico' => "Fonte: INEP - Indicadores da Educação Básica {$anoAtual}",
                'Educação profissional e tecnológica' => "Fonte: SETEC/MEC - Censo da Educação Profissional {$anoAtual}",
                'Evasão escolar' => "Fonte: UNICEF - Cenário da Exclusão Escolar no Brasil {$anoAtual}",
                'Saúde mental e políticas públicas' => "Fonte: MS - Relatório de Saúde Mental {$anoAtual}",
                'Tecnologia na educação' => "Fonte: CIEB - Censo de Tecnologia Educacional {$anoAtual}",
                'Vacinação e imunização' => "Fonte: PNI - Cobertura Vacinal Brasileira {$anoAtual}",
                'Acesso à medicina especializada' => "Fonte: ANS - Mapa Assistencial da Saúde Suplementar {$anoAtual}"
            ],
            'Segurança Pública' => [
                'Combate ao tráfico de drogas' => "Fonte: MJSP - Relatório de Segurança Pública {$anoAtual}",
                'Violência contra mulheres' => "Fonte: Fórum Brasileiro de Segurança Pública - Dados {$anoAtual}",
                'Condições do sistema prisional' => "Fonte: CNJ - Levantamento Nacional de Prisões {$anoAtual}",
                'Crimes cibernéticos' => "Fonte: Safernet Brasil - Indicadores de Denúncias {$anoAtual}",
                'Segurança nas fronteiras' => "Fonte: PF - Operações de Controle Fronteiriço {$anoAtual}",
                'Violência policial' => "Fonte: MDH - Relatório de Violações de Direitos {$anoAtual}",
                'Criminalidade juvenil' => "Fonte: SINASE - Sistema Nacional de Atendimento Socioeducativo {$anoAtual}",
                'Investimentos em inteligência' => "Fonte: SENASP - Políticas Integradas de Segurança {$anoAtual}"
            ],
            'Desenvolvimento Social' => [
                'Combate à pobreza e desigualdade' => "Fonte: IBGE - Pesquisa Nacional por Amostra de Domicílios {$anoAtual}",
                'Programas de transferência de renda' => "Fonte: Ministério da Cidadania - Relatório Social {$anoAtual}",
                'Segurança alimentar e nutricional' => "Fonte: FAO - Indicadores de Fome e Nutrição {$anoAtual}",
                'Políticas de habitação popular' => "Fonte: MDR - Programa Casa Verde e Amarela {$anoAtual}",
                'Inclusão de pessoas com deficiência' => "Fonte: IBGE - Censo Demográfico e Pessoas com Deficiência {$anoAtual}",
                'Envelhecimento populacional' => "Fonte: IPEA - Indicadores Sociodemográficos {$anoAtual}",
                'Políticas para juventude' => "Fonte: SNJ - Mapa da Juventude Brasileira {$anoAtual}",
                'Equidade racial' => "Fonte: SEPPIR - Relatório de Políticas de Igualdade Racial {$anoAtual}"
            ]
        ];

        // Função auxiliar para verificar se o dado de 2025 existe (simulação)
        $verificarDisponibilidade = function ($fonte) use ($anoAtual, $anoAnterior) {
            // Aqui você poderia integrar uma API ou consulta real para verificar a existência do dado
            // Por ora, simulamos que 2025 pode não estar disponível em alguns casos
            $dadoDisponivel2025 = rand(0, 1); // Simulação: 0 = não disponível, 1 = disponível
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
