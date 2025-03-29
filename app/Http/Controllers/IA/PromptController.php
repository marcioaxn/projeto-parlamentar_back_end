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
                    'model' => 'gpt-4-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Você é um analista político especializado em fornecer resumos estratégicos dinâmicos para parlamentares. Cada tema deve trazer novas abordagens diariamente para evitar repetições. Os dados devem ser precisos, com fontes oficiais e impacto direto para o parlamentar.'],

                        [
                            'role' => 'user',
                            'content' => "\ud83d\udccc **Resumo Executivo elaborado em " . date('d/m/Y') . " às " . date('H:i') . "**  \n\n"
                                . $this->gerarResumoDinamico($sglUfRepresentacao)
                        ]
                    ],
                ]);

                return $response->choices[0]->message->content ?? 'Sem resposta da openAI, favor aguardar um novo processamento que ocorre às 07:00 e às 13:00 diariamente.';
            });
        }

        return 'Informações insuficientes para gerar o resumo.';
    }

    private function gerarResumoDinamico($uf)
    {
        $temas = [
            'Economia & Empregos' => [
                'Crescimento do setor industrial',
                'Desafios do agronegócio',
                'Impacto do turismo na geração de empregos'
            ],
            'Infraestrutura & Obras' => [
                'Expansão de rodovias e ferrovias',
                'Situação de aeroportos e portos',
                'Projetos de saneamento e abastecimento',
                'Mobilidade urbana e transporte público',
                'Habitação e regularização fundiária',
                'Energia renovável e matriz energética',
                'Infraestrutura digital e conectividade',
                'Gestão de resíduos sólidos',
                'Prevenção de desastres naturais',
                'Revitalização de áreas urbanas degradadas'
            ],
            'Educação & Saúde' => [
                'Financiamento do ensino superior',
                'Déficit de médicos no interior',
                'Expansão da rede de escolas técnicas',
                'Qualidade do ensino básico',
                'Infraestrutura hospitalar e equipamentos',
                'Programas de prevenção e promoção da saúde',
                'Saúde mental e bem-estar social',
                'Combate às epidemias e vigilância sanitária',
                'Inclusão escolar e acessibilidade educacional'
            ],
            'Segurança Pública' => [
                'Combate ao tráfico de drogas',
                'Violência contra mulheres',
                'Condições do sistema prisional',
                'Modernização das forças policiais',
                'Segurança nas fronteiras',
                'Prevenção à violência juvenil',
                'Tecnologia aplicada à segurança pública',
                'Políticas de desarmamento',
                'Policiamento comunitário e preventivo',
                'Cooperação interestadual em segurança'
            ],
            'Desenvolvimento Social' => [
                'Combate à pobreza e desigualdade',
                'Programas de transferência de renda',
                'Segurança alimentar e nutricional',
                'Políticas para juventude',
                'Acessibilidade e inclusão de pessoas com deficiência',
                'Proteção à criança e ao adolescente',
                'Políticas para idosos e envelhecimento ativo',
                'Igualdade racial e combate à discriminação',
                'Direitos dos povos tradicionais e indígenas',
                'Economia solidária e cooperativismo'
            ]
        ];

        $resumo = "";
        $contador = 1;
        foreach ($temas as $tema => $subtemas) {
            $subtema = $subtemas[array_rand($subtemas)];
            $resumo .= "{$contador}\uFE0F\u20E3 **{$tema}**  \n\n
- {$subtema} no estado do {$uf}. [Dados detalhados e fontes oficiais aqui].  \n\n
🔹 **Oportunidade**: [Informação específica].  \n
🎯 **Ação Sugerida**: [Medida concreta].  \n\n
            ";
            $contador++;
        }

        return $resumo;
    }
}
