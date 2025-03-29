<?php

namespace App\Http\Controllers\IA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
                    'model' => 'gpt-4-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Você é um analista político especializado em fornecer **resumos executivos estratégicos** para parlamentares.  

                        🔹 Cada informação deve conter **dados concretos, impacto real e direcionamento prático** para o senador.  
                        🔹 **Evite estruturas engessadas e repetitivas**. O conteúdo deve ser variado e relevante ao contexto.  
                        🔹 **Inclua percentuais, valores em reais e possíveis ações** que o parlamentar pode tomar.  
                        🔹 O resumo deve ser atualizado e específico para o estado do parlamentar.'],

                        [
                            'role' => 'user',
                            'content' =>
                            "📌 **Resumo Executivo para {$cargoParlamentar} {$nomeParlamentar} ({$sglPartido}-{$sglUfRepresentacao})**  

1️⃣ **Economia & Empregos**  
- A taxa de desemprego no {$sglUfRepresentacao} subiu **{X%}** no último trimestre, superando a média nacional de **{Y%}**. O setor **{setor afetado}** foi o mais impactado, registrando queda de **{Z%}** na geração de empregos.  
🔹 **Oportunidade**: O governo federal lançou o programa **{nome do programa}**, que prevê investimentos de **R$ {valor}** para incentivar a contratação de trabalhadores no setor.  
🎯 **Ação Sugerida**: Cobrar do Ministério da Fazenda a inclusão do {$sglUfRepresentacao} na primeira fase de implementação do programa.  

2️⃣ **Infraestrutura & Obras**  
- As obras da rodovia **{nome da rodovia}**, que liga **{cidade}** a **{cidade}**, estão **paralisadas há {X meses}** devido à falta de repasses federais. Isso impacta diretamente o transporte de **{produto/setor afetado}**, que já acumula perdas de **R$ {valor}**.  
🔹 **Movimentação**: O DNIT solicitou um novo aporte de **R$ {valor}**, mas aguarda liberação da **{instituição responsável}**.  
🎯 **Ação Sugerida**: Mobilizar a bancada do estado para acelerar a liberação dos recursos junto ao Ministério da Infraestrutura.  

3️⃣ **Educação & Saúde**  
- O novo modelo de distribuição do Fundeb pode reduzir em **R$ {valor}** os repasses para escolas públicas no {$sglUfRepresentacao}. Municípios menores serão os mais prejudicados, com cortes médios de **{X%}** no orçamento escolar.  
🔹 **Reação do Congresso**: A Comissão de Educação do Senado propôs um ajuste no cálculo do fundo para minimizar perdas nos estados do Norte.  
🎯 **Ação Sugerida**: Articular com a Comissão de Educação para garantir que o {$sglUfRepresentacao} tenha compensação financeira no novo modelo.  

4️⃣ **Segurança Pública**  
- O índice de violência no {$sglUfRepresentacao} aumentou **{X%}** nos últimos seis meses, com crescimento expressivo em **{tipo de crime}**. A cidade de **{cidade mais afetada}** registrou **{X homicídios/furtos/roubos}** em {período}, o maior índice desde {ano}.  
🔹 **Medidas do Governo**: O Ministério da Justiça anunciou um pacote de segurança de **R$ {valor}**, mas apenas **{X%}** será destinado ao {$sglUfRepresentacao}.  
🎯 **Ação Sugerida**: Reivindicar maior participação do estado no programa e pressionar pela inclusão de municípios estratégicos.  

📌 **Resumo atualizado e com foco em ações concretas para o parlamentar.**"
                        ]
                    ],
                ]);

                return $response->choices[0]->message->content ?? 'No response from OpenAI';
            });
        }

        return 'Informações insuficientes para gerar o resumo.';
    }
}
