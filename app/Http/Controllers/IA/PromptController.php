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

            // Criando uma chave única para o cache, garantindo que ela se renove a cada 2 horas
            $cacheKey = "resumo_executivo_{$cargoParlamentar}_{$nomeParlamentar}_{$sglUfRepresentacao}";

            // Cache::forget($cacheKey);

            return Cache::remember($cacheKey, now()->addHours(2), function () use ($nomeParlamentar, $cargoParlamentar, $sglPartido, $sglUfRepresentacao) {
                // Chamando a OpenAI com o mesmo prompt original
                $response = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Você é um assistente político altamente especializado. Seu papel é fornecer um resumo executivo diário para um parlamentar, destacando as principais pautas políticas em discussão no Congresso Nacional e como elas impactam diretamente seu estado de representação. A resposta deve ser clara, concisa e informativa, destacando oportunidades e desafios políticos.'],
                        ['role' => 'user', 'content' => "Sou o(a) " . $cargoParlamentar . " " . $nomeParlamentar . ", do partido " . $sglPartido . ", representando o estado do " . $sglUfRepresentacao . ". Preciso de um resumo executivo das pautas políticas mais relevantes hoje, dia " . date('d/m/Y') . ", no Congresso Nacional e no cenário estadual e como elas impactam diretamente o estado do " . $sglUfRepresentacao . ". Estruture a resposta nos seguintes temas:

1️⃣ **Emprego e Economia**  
- Há alguma pauta em discussão no Congresso que impacta o desenvolvimento econômico do estado de AP?  
- Como está o cenário de emprego e geração de renda no Brasil e no Amapá?  
- Há indicadores econômicos relevantes divulgados recentemente?  

2️⃣ **Energia e Infraestrutura**  
- Alguma proposta no Congresso trata de investimentos ou mudanças em energia e infraestrutura que afetam AP?  
- Como está a evolução de obras e projetos de infraestrutura relevantes para o estado?  

3️⃣ **Educação e Saúde**  
- Alguma medida em tramitação no Congresso impacta a educação básica, superior ou a saúde pública no estado?  
- Como estão os indicadores de educação e saúde do Amapá comparados ao cenário nacional?  

4️⃣ **Moradia e Terras**  
- Existem discussões no Congresso sobre políticas habitacionais, reforma agrária ou regularização fundiária que impactam AP?  
- Há movimentações políticas relacionadas a povos indígenas, quilombolas ou assentamentos rurais na região?  

5️⃣ **Desigualdade e Inclusão**  
- Há propostas legislativas que afetam políticas sociais, combate à desigualdade e inclusão no estado?  
- O Congresso está debatendo alguma medida que impacta diretamente grupos vulneráveis no Amapá?  

⚡ **IMPORTANTE**:  
✅ Seja breve e objetivo – preciso de um resumo executivo, não um relatório extenso.  
✅ Priorize informações novas – evite repetir temas de dias anteriores, a menos que haja novidades relevantes.  

Agora, gere um resumo executivo das informações políticas mais relevantes do dia para o senador Randolfe Rodrigues."]
                    ],
                ]);

                return $response->choices[0]->message->content ?? 'No response from OpenAI';
            });
        }

        return 'Informações insuficientes para gerar o resumo.';
    }
}
