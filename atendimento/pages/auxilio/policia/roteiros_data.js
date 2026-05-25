// =====================================================================
// BASE DE DADOS DOS ROTEIROS DE ATENDIMENTO (190)
// =====================================================================

const ROTEIROS_DATA = [
  // ==================== PATRIMÔNIO ====================
  { 
    id: "furto", 
    titulo: "Furto (Sem violência)", 
    categoria: "PATRIMONIO",
    gerarHTML: () => `
      <h2>📘 ROTEIRO OPERACIONAL – FURTO (190)</h2>
      
      <div id="step-1" class="roteiro-step">
        <h3>Passo 1: Abordagem Inicial</h3>
        <blockquote>“Centro de Atendimento: Qual é a sua emergência?”</blockquote>
        <p style="color: var(--text-muted); font-size: 13px;">Persistindo sem resposta → qualificar como Falha na Comunicação.</p>

        <h3>Passo 2: Identificação da Natureza</h3>
        ${qSimNao("O fato está acontecendo agora?")}
        ${qSimNao("O autor ainda está no local?")}
        ${qTexto("O que foi furtado?")}
        ${qTexto("Há suspeito identificado? (Se sim, descreva)", "Características, roupas...")}
        ${qSimNao("Existe câmera de segurança?")}
        ${qSimNao("Houve arrombamento?")}
        
        <div class="step-navigation">
          <button class="btn-step" onclick="mudarPasso(2)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-2" class="roteiro-step" style="display: none;">
        <h3>Passo 3: Dados Específicos (Veículo)</h3>
        
        <div class="interactive-q">
          <span class="q-text">Foi furto de veículo?</span>
          <div class="q-actions">
            <button class="btn-sn sim" onclick="handleFurtoVeiculo(this, true)">Sim</button>
            <button class="btn-sn nao" onclick="handleFurtoVeiculo(this, false)">Não</button>
          </div>
        </div>

        <div id="perguntas-veiculo" style="display: none; padding-left: 15px; border-left: 3px solid var(--primary-light); margin-bottom: 20px;">
          ${qTexto("Qual a Placa do veículo? (Confirmar por palavra)", "Ex: T de Tatu...")}
          ${qTexto("Marca, Modelo e Cor?")}
          ${qTexto("Local exato e Horário aproximado em que deixou o veículo?")}
          <p style="font-size: 13px; color: var(--text-muted);">* Consultar antes de criar para evitar duplicidade. Inserir corretamente na aba “Veículos”. Se estiver em deslocamento sem saber o endereço exato, registrar ENDEREÇO APROXIMADO.</p>
        </div>

        <h3>Passo 4: Análise de Flagrância (Ponto Decisivo)</h3>
        <p><strong>🔴 CRÍTICO - FURTO EM ANDAMENTO (Autor no local/visualizado):</strong></p>
        <ul style="font-size: 14px;">
          <li>Criar chamada, Elevar prioridade, Marcar “Alerta”.</li>
          <li>Inserir na primeira linha: <strong>FATO EM ANDAMENTO</strong>.</li>
          <li style="color: #ef4444;">NUNCA orientar registro presencial se estiver em flagrante.</li>
        </ul>

        <p><strong>🟡 ATENÇÃO - FURTO RECENTE (Autor acabou de fugir):</strong></p>
        <ul style="font-size: 14px;">
          <li>Criar chamada, prioridade conforme avaliação.</li>
          <li>Registrar direção de fuga.</li>
        </ul>

        <p><strong>🟢 INFORMAÇÃO - FURTO PRETÉRITO (Sem autor, já consolidado):</strong></p>
        <p style="font-size: 14px;">Não criar chamada para envio de recurso sem possibilidade de intervenção.</p>
        <blockquote>“SENHOR(A), ORIENTO QUE PROCURE UMA UNIDADE POLICIAL PARA REGISTRO DO FATO.”</blockquote>
        
        <div class="step-navigation" style="justify-content: space-between;">
          <button class="btn-step btn-back" onclick="mudarPasso(1)">⬅️ Voltar</button>
          <button class="btn-step" onclick="mudarPasso(3)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-3" class="roteiro-step" style="display: none;">
        <h3>Passo 5: Criação da Chamada (Quando Cabível)</h3>
        <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 8px;">Seguir rigorosamente o POP de Criação de Chamada:</p>
        <ul style="font-size: 14px;">
          <li>✔ Registrar primeiro o endereço do fato</li>
          <li>✔ Confirmar nome e telefone</li>
          <li>✔ Escolher natureza correta (ex: Furto Consumado / Furto em Andamento)</li>
          <li>✔ Evitar textos longos e prolixidade</li>
        </ul>
        <p style="font-size: 14px;"><strong>Se solicitante pedir anonimato:</strong> Registrar como "Anônimo" e inserir no histórico: <strong>PRESERVAR O ANONIMATO DO SOLICITANTE</strong>.</p>

        <h3 style="margin-top: 15px;">Passo 6: Vítima Aflita (Fraseologia)</h3>
        <blockquote>“SENHOR(A), SUA SOLICITAÇÃO JÁ FOI REGISTRADA. SOLICITO QUE FIQUE CALMO(A) E ME REPASSE MAIS ALGUNS DADOS PARA FACILITAR O ATENDIMENTO.”</blockquote>

        <h3>Passo 7: Encerramento Padronizado</h3>
        <p><strong>Se houver envio de viatura:</strong></p>
        <blockquote>“SENHOR(A), SUA SOLICITAÇÃO JÁ ESTÁ REGISTRADA. ASSIM QUE POSSÍVEL UMA VIATURA SERÁ ENVIADA AO LOCAL.”</blockquote>
        <p style="color: #b91c1c; font-size: 14px;">Não informar tempo de atendimento, não discutir, manter objetividade.</p>
        <div class="step-navigation">
          <button class="btn-step btn-back" onclick="mudarPasso(2)">⬅️ Voltar</button>
        </div>
      </div>
    `
  },
  
  { 
    id: "roubo", 
    titulo: "Roubo (Crime violento)", 
    categoria: "PATRIMONIO",
    gerarHTML: () => `
      <h2>📘 ROTEIRO OPERACIONAL – ROUBO (190)</h2>
      
      <div id="step-1" class="roteiro-step">
        <h3>Passo 1: Abordagem Inicial</h3>
        <p style="color: var(--text-muted); font-size: 13px;">Conforme POP 2.07.002/2025:</p>
        <blockquote>“Centro de Atendimento: Qual é a sua emergência??”</blockquote>
        <ul style="font-size: 13px;">
          <li>Se não houver resposta, repetir até três vezes, intercalando silêncio.</li>
          <li>Caso permaneça sem resposta: encerrar como “Falha na Comunicação” no CAD.</li>
        </ul>

        <h3>Passo 2: Identificação da Natureza</h3>
        ${qSimNao("O roubo está acontecendo agora?")}
        ${qTexto("Quantos autores e como estavam vestidos?", "Ex: 2 indivíduos de preto...")}
        ${qTexto("Estão armados? Que tipo de arma?")}
        ${qSimNao("Há vítimas feridas?")}
        ${qTexto("Veículo utilizado? (modelo, cor, placa com confirmação por palavra T de Tatu)")}
        ${qTexto("Sentido da fuga?")}
        
        <div class="step-navigation">
          <button class="btn-step" onclick="mudarPasso(2)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-2" class="roteiro-step" style="display: none;">
        <h3>Passo 3: Criação da Chamada no CAD</h3>
        <p style="color: var(--text-muted); font-size: 13px;">Seguir POP 2.07.003/2025. Ordem correta:</p>
        <ul>
          <li>- Registrar primeiro o endereço do fato</li>
          <li>- Confirmar nome e telefone do solicitante</li>
          <li>- Escolher natureza compatível (ex: Roubo Consumado / Roubo em Andamento)</li>
          <li>- Inserir envolvidos e veículos nas abas corretas</li>
        </ul>

        <h3>Passo 4: Análise de Situação Crítica</h3>
        <p style="color: #ef4444; font-weight: bold;">🔴 CRÍTICO - ROUBO EM ANDAMENTO:</p>
        <ul>
          <li>Elevar prioridade imediatamente (Se risco à vida = prioridade máxima).</li>
          <li>Marcar campo “Alerta”.</li>
          <li>Inserir na primeira linha do histórico: <strong>FATO EM ANDAMENTO</strong>.</li>
        </ul>

        <p style="font-weight: bold; margin-top: 10px;">🟡 ATENÇÃO - Se Não Estiver Mais em Flagrante:</p>
        <p style="font-size: 14px;">Criar chamada se houver possibilidade de intervenção imediata. Se for fato totalmente pretérito sem possibilidade de ação, orientar registro em Unidade Policial.</p>

        <div class="step-navigation" style="justify-content: space-between;">
          <button class="btn-step btn-back" onclick="mudarPasso(1)">⬅️ Voltar</button>
          <button class="btn-step" onclick="mudarPasso(3)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-3" class="roteiro-step" style="display: none;">
        <h3>Passo 5: Cuidados e Vítima Aflita</h3>
        <p style="color: var(--text-muted); font-size: 13px;">Fraseologia Padronizada (tom firme, sem gritar):</p>
        <blockquote>“SENHOR(A), SUA SOLICITAÇÃO JÁ FOI REGISTRADA. SOLICITO QUE O SENHOR(A) FIQUE CALMO(A) E ME REPASSE MAIS ALGUNS DADOS PARA FACILITAR O ATENDIMENTO.”</blockquote>

        <p style="font-weight: bold; margin-top: 10px;">🚨 Pontos Críticos:</p>
        <ul style="color: #b91c1c; font-size: 14px;">
          <li>NÃO orientar registro presencial se estiver em flagrante.</li>
          <li>NÃO emitir opinião pessoal ou prolongar o atendimento.</li>
          <li>GARANTIR anonimato se solicitado (registrar corretamente no CAD).</li>
        </ul>

        <h3>Passo 6: Encerramento Padronizado</h3>
        <blockquote>“SENHOR(A), SUA SOLICITAÇÃO JÁ ESTÁ REGISTRADA. ASSIM QUE POSSÍVEL UMA VIATURA SERÁ ENVIADA AO LOCAL.”</blockquote>
        <p style="color: #b91c1c; font-size: 13px;">NÃO informar tempo estimado de chegada da viatura.</p>
        <div class="step-navigation">
          <button class="btn-step btn-back" onclick="mudarPasso(2)">⬅️ Voltar</button>
        </div>
      </div>
    `
  },

  // ==================== PESSOA ====================
  { 
    id: "mulher", 
    titulo: "Violência Contra a Mulher", 
    categoria: "PESSOA",
    gerarHTML: () => `
      <h2>📘 ROTEIRO OPERACIONAL – VIOLÊNCIA DOMÉSTICA (190)</h2>
      
      <div id="step-1" class="roteiro-step">
        <h3>Passo 1: Abordagem Inicial e Risco</h3>
        <blockquote>“Centro de Atendimento: Qual é a sua emergência??”</blockquote>
        <p style="color: var(--text-muted); font-size: 13px;">Se a vítima estiver chorando ou sussurrando, reduza o tom, evite perguntas longas e priorize a segurança.</p>
        
        ${qSimNao("O agressor está no local?")}
        ${qSimNao("Ele está armado?")}
        ${qSimNao("A senhora está ferida?")}
        ${qSimNao("Há crianças na residência?")}
        ${qSimNao("Consegue sair do local com segurança?")}

        <p style="color: #ef4444; font-weight: bold; margin-top: 8px;">🔴 Se estiver em andamento → tratar como FATO EM ANDAMENTO.</p>
        
        <div class="step-navigation">
          <button class="btn-step" onclick="mudarPasso(2)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-2" class="roteiro-step" style="display: none;">
        <h3>Passo 2: Informações Essenciais (Histórico)</h3>
        ${qTexto("Qual o nome do agressor e a relação com a vítima?", "Ex: João, ex-marido...")}
        ${qTexto("Existe histórico de agressões anteriores?")}
        ${qSimNao("A vítima possui Medida Protetiva?")}
        ${qSimNao("Houve uso de álcool ou drogas?")}
        
        <h3>Passo 3: Criação da Chamada (Obrigatória)</h3>
        <p style="color: #ef4444; font-weight: bold;">🔴 CRÍTICO - Sempre que houver flagrância ou descumprimento de medida:</p>
        <ul style="font-size: 14px;">
          <li>Marcar campo “Alerta” e <strong>PRIORIDADE ALTA</strong>.</li>
          <li>Inserir na primeira linha do histórico: <strong>VIOLÊNCIA DOMÉSTICA</strong>.</li>
        </ul>

        <div class="step-navigation" style="justify-content: space-between;">
          <button class="btn-step btn-back" onclick="mudarPasso(1)">⬅️ Voltar</button>
          <button class="btn-step" onclick="mudarPasso(3)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-3" class="roteiro-step" style="display: none;">
        <h3>Passo 4: Anonimato e Situações</h3>
        <p style="font-size: 14px;"><strong>Terceiros denunciando:</strong> Registrar como "Anônimo" se solicitado e inserir PRESERVAR O ANONIMATO DO SOLICITANTE.</p>
        <p style="font-size: 14px;"><strong>🟢 INFORMAÇÃO - Fato Pretérito:</strong> Avaliar com cautela antes de deixar de criar chamada, pois violência doméstica envolve risco continuado.</p>

        <h3>Passo 5: O que NÃO Fazer</h3>
        <ul style="color: #b91c1c; font-size: 14px;">
          <li>❌ Não questionar a veracidade.</li>
          <li>❌ Não aconselhar separação ou reconciliação.</li>
          <li>❌ Não minimizar (“isso acontece”).</li>
          <li>❌ Não informar tempo de chegada da viatura.</li>
        </ul>

        <h3>Passo 6: Encerramento Padronizado</h3>
        <blockquote>“SENHORA, SUA SOLICITAÇÃO JÁ ESTÁ REGISTRADA. ASSIM QUE POSSÍVEL UMA VIATURA SERÁ ENVIADA AO LOCAL.”</blockquote>
        <div class="step-navigation">
          <button class="btn-step btn-back" onclick="mudarPasso(2)">⬅️ Voltar</button>
        </div>
      </div>
    `
  },

  { 
    id: "agressao", 
    titulo: "Agressão", 
    categoria: "PESSOA",
    gerarHTML: () => `
      <h2>📘 ROTEIRO – AGRESSÃO (190)</h2>
      
      <div id="step-1" class="roteiro-step">
        <h3>Passo 1: Abertura e Triagem</h3>
        <blockquote>“Centro de Atendimento: Qual é a sua emergência??”</blockquote>

        ${qSimNao("A agressão está acontecendo agora?")}
        ${qTexto("Onde exatamente?", "Endereço completo")}
        ${qTexto("Quantos estão envolvidos?", "Quantidade de pessoas...")}
        ${qTexto("Há uso de arma?", "Se sim, qual tipo?")}
        ${qSimNao("Alguém está ferido?")}
        ${qSimNao("O autor ainda está no local?")}
        
        <div class="step-navigation">
          <button class="btn-step" onclick="mudarPasso(2)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-2" class="roteiro-step" style="display: none;">
        <h3>Passo 2: Classificação Operacional</h3>
        <p><strong>🔴 CRÍTICO - AGRESSÃO EM ANDAMENTO:</strong></p>
        <ul style="font-size: 14px;">
          <li>Criar chamada, Marcar “Alerta”, inserir FATO EM ANDAMENTO.</li>
          <li>Elevar prioridade (Se houver ferido grave = prioridade máxima).</li>
        </ul>

        <p><strong>🟡 ATENÇÃO - AGRESSÃO RECENTE (autor fugiu):</strong></p>
        <ul style="font-size: 14px;"><li>Criar chamada e registrar direção de fuga.</li></ul>

        <p><strong>🟢 INFORMAÇÃO - AGRESSÃO PRETÉRITA (sem flagrante):</strong></p>
        <p style="font-size: 14px; color: #ef4444;">⚠ Sem autor no local, sem risco atual: Orientar registro em Unidade Policial.</p>

        <h3>Passo 3: Encerramento</h3>
        <blockquote>“SUA SOLICITAÇÃO JÁ ESTÁ REGISTRADA. ASSIM QUE POSSÍVEL UMA VIATURA SERÁ ENVIADA AO LOCAL.”</blockquote>
        <div class="step-navigation" style="justify-content: flex-start;">
          <button class="btn-step btn-back" onclick="mudarPasso(1)">⬅️ Voltar</button>
        </div>
      </div>
    `
  },

  { 
    id: "discussao", 
    titulo: "Discussão / Atrito Verbal", 
    categoria: "PESSOA",
    gerarHTML: () => `
      <h2>📘 ROTEIRO – DISCUSSÃO / ATRITO VERBAL (190)</h2>
      <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 12px;">Diferenciar conflito leve de risco evolutivo.</p>

      <div id="step-1" class="roteiro-step">
        <h3>Passo 1: Triagem Evolutiva</h3>
        <blockquote>"Centro de Atendimento: Qual é a sua emergência?”</blockquote>
        
        ${qSimNao("Está havendo agressão física?")}
        ${qSimNao("Há ameaça com uso de arma?")}
        ${qSimNao("Alguém está ferido?")}
        ${qSimNao("O conflito está evoluindo?")}
        ${qTexto("Envolve quem? (familiares, vizinhos, trânsito)", "Descreva os envolvidos...")}

        <div class="step-navigation">
          <button class="btn-step" onclick="mudarPasso(2)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-2" class="roteiro-step" style="display: none;">
        <h3>Passo 2: Avaliação e Classificação</h3>
        <p><strong>🔴 CRÍTICO - DISCUSSÃO COM RISCO IMEDIATO:</strong></p>
        <p style="font-size: 14px;">Se houver ameaça, arma ou tentativa de agressão: Criar chamada, avaliar "Alerta" e registrar se está em andamento (Prioridade Média/Alta).</p>
        
        <p><strong>🟡 ATENÇÃO - DISCUSSÃO ACALORADA (SEM AGRESSÃO):</strong></p>
        <p style="font-size: 14px;">Se for apenas verbal sem risco iminente: Criar chamada apenas se houver necessidade de intervenção preventiva.</p>

        <p><strong>🟢 INFORMAÇÃO - DESENTENDIMENTO SEM RISCO ATUAL:</strong></p>
        <p style="font-size: 14px;">Exemplo: Discussão encerrada, quer "registrar que brigou". Orientar procurar Unidade Policial.</p>

        <h3>Passo 3: Cuidados Específicos</h3>
        <ul style="color: #b91c1c; font-size: 14px;">
          <li>❌ Não tomar partido.</li>
          <li>❌ Não dar conselho pessoal ou mediar pelo telefone.</li>
          <li>✔ Manter objetividade (tempo médio até 3 minutos).</li>
        </ul>
        
        <div class="step-navigation">
          <button class="btn-step btn-back" onclick="mudarPasso(1)">⬅️ Voltar</button>
        </div>
      </div>
    `
  },

  // ==================== DROGAS ====================
  { 
    id: "trafico_ocorrendo", 
    titulo: "Tráfico Ocorrendo Agora", 
    categoria: "DROGAS",
    gerarHTML: () => `
      <h2>📘 ROTEIRO 1 – TRÁFICO EM ANDAMENTO (FLAGRANTE)</h2>

      <div id="step-1" class="roteiro-step">
        <h3>Passo 1: Confirmação de Flagrante</h3>
        <blockquote>"Centro de Atendimento: Qual é a sua emergência?"</blockquote>
        
        ${qSimNao("A venda está acontecendo exatamente agora?")}
        ${qTexto("Quantas pessoas envolvidas e como estão vestidas?", "Ex: 2 de boné, 1 de camisa azul...")}
        ${qSimNao("Está ocorrendo em via pública? (Se não, diga onde)")}
        ${qSimNao("Há arma de fogo visível?")}
        ${qTexto("Há veículo envolvido? (placa por palavra)")}
        ${qSimNao("O senhor(a) está em segurança?")}

        <div class="step-navigation">
          <button class="btn-step" onclick="mudarPasso(2)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-2" class="roteiro-step" style="display: none;">
        <h3>Passo 2: Criação da Chamada e Marcação</h3>
        <ul style="font-size: 14px;">
          <li>Registrar primeiro o endereço do fato e confirmar telefone.</li>
          <li>Escolher natureza compatível (Tráfico de Drogas).</li>
        </ul>
        
        <p style="color: #ef4444; font-weight: bold; margin-top: 10px;">🔴 CRÍTICO - Se estiver ocorrendo no momento:</p>
        <ul style="font-size: 14px;">
          <li>Marcar campo “Alerta” e Elevar prioridade (arma = prioridade ainda maior).</li>
          <li>Inserir na primeira linha do histórico: <strong>FATO EM ANDAMENTO</strong>.</li>
        </ul>

        <h3>Passo 3: Anonimato e Encerramento</h3>
        <p style="font-size: 14px;">Se solicitante pedir sigilo: Registrar como “Anônimo” e inserir <strong>PRESERVAR O ANONIMATO DO SOLICITANTE</strong>. Garantir sigilo absoluto.</p>
        
        <blockquote>“SENHOR(A), SUA SOLICITAÇÃO JÁ ESTÁ REGISTRADA. O ATENDIMENTO OCORRE CONFORME O GRAU DE PRIORIDADE.”</blockquote>
        <ul style="color: #b91c1c; font-size: 14px;">
          <li>⚠ Não informar tempo de chegada.</li>
          <li>⚠ Não informar estratégia operacional.</li>
        </ul>

        <div class="step-navigation" style="justify-content: flex-start;">
          <button class="btn-step btn-back" onclick="mudarPasso(1)">⬅️ Voltar</button>
        </div>
      </div>
    `
  },

  { 
    id: "denuncia_trafico", 
    titulo: "Denúncia de Tráfico", 
    categoria: "DROGAS",
    gerarHTML: () => `
      <h2>📘 DENÚNCIA / SUSPEITA DE TRÁFICO (SEM FLAGRANTE)</h2>

      <div id="step-1" class="roteiro-step">
        <h3>Passo 1: Levantamento de Dados</h3>
        <blockquote>"Centro de Atendimento: Qual é a sua emergência?"</blockquote>
        
        ${qSimNao("É informação recorrente?")}
        ${qTexto("O que exatamente o senhor(a) observou?")}
        ${qTexto("Há dias ou horários específicos?", "Ex: Todo dia à noite...")}
        ${qTexto("Como é a movimentação? (Entra e sai rápido?)", "Descreva o que foi visto...")}
        ${qTexto("Quem são os suspeitos? (Nomes, apelidos, características)")}
        ${qSimNao("Existe informação de arma no local?")}

        <div class="step-navigation">
          <button class="btn-step" onclick="mudarPasso(2)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-2" class="roteiro-step" style="display: none;">
        <h3>Passo 2: Classificação e Encaminhamento</h3>
        <p><strong>🔴 CRÍTICO - Se evoluir para flagrante:</strong> Aplicar roteiro Tráfico Ocorrendo Agora (marcar Alerta).</p>
        <p><strong>🟡 ATENÇÃO - Se for denúncia sem flagrante:</strong> Criar chamada para registro e encaminhamento. Não marcar “Alerta”. Prioridade conforme avaliação.</p>
        <p><strong>🟢 INFORMAÇÃO - Se for apenas suspeita vaga:</strong> "Acho que meu vizinho mexe com coisa errada" → Solicitar dados objetivos. Se não houver, orientar canais próprios de denúncia anônima (Disque Denúncia 181). Não criar chamada sem base mínima.</p>

        <h3>Passo 3: Cuidados Operacionais</h3>
        <ul style="color: #b91c1c; font-size: 14px;">
          <li>❌ Nunca revelar se já há investigação.</li>
          <li>❌ Não comentar histórico do local.</li>
          <li>❌ Não dar retorno operacional.</li>
        </ul>
        <div class="step-navigation" style="justify-content: flex-start;">
          <button class="btn-step btn-back" onclick="mudarPasso(1)">⬅️ Voltar</button>
        </div>
      </div>
    `
  },

  // ==================== SEXUAL ====================
  { 
    id: "estupro", 
    titulo: "Estupro", 
    categoria: "SEXUAL",
    gerarHTML: () => `
      <h2>📘 ROTEIRO – ESTUPRO (Crime Sexual com Conjunção Carnal)</h2>
      <p style="color: #ef4444; font-size: 13px; font-weight: bold; margin-bottom: 12px;">⚠ Aqui o cuidado é máximo. Atendimento técnico e acolhedor.</p>

      <div id="step-1" class="roteiro-step">
        <h3>Passo 1: Abertura e Primeira Avaliação</h3>
        <blockquote>"Centro de Atendimento: Qual é a sua emergência?"</blockquote>
        <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 15px;">Se a vítima estiver em choque: falar pausadamente e priorizar segurança imediata.</p>

        ${qSimNao("O agressor ainda está no local?")}
        ${qSimNao("A senhora está em segurança neste momento?")}
        ${qSimNao("Precisa de atendimento médico (Ambulância)?")}
        ${qTexto("Onde ocorreu?")}
        ${qTexto("Quando ocorreu?")}

        <div class="step-navigation">
          <button class="btn-step" onclick="mudarPasso(2)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-2" class="roteiro-step" style="display: none;">
        <h3>Passo 2: Classificação Operacional</h3>
        <p><strong>🔴 CRÍTICO - EM ANDAMENTO ou autor ainda presente:</strong> Criar chamada, Marcar “Alerta”, Elevar prioridade, Inserir FATO EM ANDAMENTO.</p>
        <p><strong>🟡 ATENÇÃO - Recente (autor fugiu):</strong> Criar chamada, Registrar direção de fuga (Prioridade alta).</p>
        <p><strong>🟢 INFORMAÇÃO - Fato pretérito (sem risco atual):</strong> Criar chamada se houver necessidade de intervenção, caso contrário, orientar comparecimento à Unidade Policial (⚠ NUNCA desencorajar registro).</p>

        <h3>Passo 3: Orientações Sensíveis e Encerramento</h3>
        <ul style="color: #b91c1c; font-size: 14px;">
          <li>❌ Não culpar.</li>
          <li>❌ Não questionar comportamento da vítima.</li>
          <li>❌ Não pedir detalhes íntimos desnecessários.</li>
          <li style="color: var(--text);">✔ Orientar preservar vestígios (não tomar banho, não trocar roupas) apenas se adequado e com cuidado.</li>
        </ul>
        
        <blockquote style="margin-top: 15px;">“SENHORA, SUA SOLICITAÇÃO JÁ ESTÁ REGISTRADA. A EQUIPE SERÁ ENVIADA.”</blockquote>
        <div class="step-navigation" style="justify-content: flex-start;">
          <button class="btn-step btn-back" onclick="mudarPasso(1)">⬅️ Voltar</button>
        </div>
      </div>
    `
  },

  { 
    id: "importunacao", 
    titulo: "Importunação Sexual", 
    categoria: "SEXUAL",
    gerarHTML: () => `
      <h2>📘 ROTEIRO – IMPORTUNAÇÃO SEXUAL</h2>
      <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 12px;">(Ex: toque sem consentimento, assédio em via pública, transporte etc.)</p>

      <div id="step-1" class="roteiro-step">
        <h3>Passo 1: Perguntas Essenciais</h3>
        <blockquote>“Centro de Atendimento: Qual é a sua emergência?”</blockquote>

        ${qSimNao("O fato está acontecendo agora?")}
        ${qSimNao("O autor ainda está no local?")}
        ${qSimNao("Houve contato físico?")}
        ${qSimNao("O local é público?")}
        ${qSimNao("Há risco de evolução para algo mais grave?")}
        ${qTexto("Descreva as características do autor.")}

        <div class="step-navigation">
          <button class="btn-step" onclick="mudarPasso(2)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-2" class="roteiro-step" style="display: none;">
        <h3>Passo 2: Classificação e Registro</h3>
        <p><strong>🔴 CRÍTICO - EM ANDAMENTO:</strong> Criar chamada, Marcar “Alerta” se risco imediato, Registrar características do autor, Prioridade conforme avaliação.</p>
        <p><strong>🟡 ATENÇÃO - Autor já saiu:</strong> Criar chamada se houver possibilidade de localização, Registrar direção de fuga.</p>
        <p><strong>🟢 INFORMAÇÃO - Fato já encerrado sem autor identificado:</strong> Orientar registro em Unidade Policial.</p>

        <h3>Passo 3: Cuidados do Atendente</h3>
        <ul style="font-size: 14px;">
          <li>✔ Utilizar linguagem respeitosa.</li>
          <li style="color: #b91c1c;">❌ Não minimizar (“foi só um toque”).</li>
          <li style="color: #b91c1c;">❌ Não emitir opinião pessoal.</li>
          <li>✔ Manter objetividade (tempo médio até 3 minutos).</li>
        </ul>
        <div class="step-navigation" style="justify-content: flex-start;">
          <button class="btn-step btn-back" onclick="mudarPasso(1)">⬅️ Voltar</button>
        </div>
      </div>
    `
  },

  // ==================== OUTROS ====================
  { 
    id: "suspeita", 
    titulo: "Atividade Suspeita", 
    categoria: "OUTROS",
    gerarHTML: () => `
      <h2>📘 ROTEIRO – ATIVIDADE SUSPEITA</h2>

      <div id="step-1" class="roteiro-step">
        <h3>Passo 1: Perguntas de Triagem</h3>
        <blockquote>“Centro de Atendimento: Qual é a sua emergência?”</blockquote>

        ${qSimNao("Está acontecendo neste exato momento?")}
        ${qTexto("O que exatamente a pessoa está fazendo?", "Ex: Forçando o portão...")}
        ${qSimNao("Está tentando arrombar algo ou observando casas?")}
        ${qSimNao("O suspeito ainda está no local?")}
        ${qTexto("Há veículo envolvido? (placa)")}

        <div class="step-navigation">
          <button class="btn-step" onclick="mudarPasso(2)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-2" class="roteiro-step" style="display: none;">
        <h3>Passo 2: Classificação e Decisão</h3>
        <p><strong>🔴 CRÍTICO - EM ANDAMENTO com indício de crime:</strong></p>
        <ul style="font-size: 14px;">
          <li>Criar chamada, registrar endereço primeiro.</li>
          <li>Marcar “Alerta” se houver risco iminente.</li>
          <li>Inserir no histórico: <strong>FATO EM ANDAMENTO</strong>.</li>
        </ul>

        <p><strong>🟡 ATENÇÃO - Pessoa suspeita sem ação concreta:</strong> Criar chamada preventiva (sem "Alerta" se não houver risco imediato).</p>
        <p><strong>🟢 INFORMAÇÃO - Informação vaga (sem dados mínimos):</strong> Solicitar mais detalhes ou orientar canais próprios.</p>

        <h3>Passo 3: Encerramento</h3>
        <blockquote>“SUA SOLICITAÇÃO JÁ ESTÁ REGISTRADA. O ATENDIMENTO OCORRE CONFORME O GRAU DE PRIORIDADE.”</blockquote>
        <div class="step-navigation" style="justify-content: flex-start;">
          <button class="btn-step btn-back" onclick="mudarPasso(1)">⬅️ Voltar</button>
        </div>
      </div>
    `
  },

  { 
    id: "som", 
    titulo: "Som Alto / Perturbação", 
    categoria: "OUTROS",
    gerarHTML: () => `
      <h2>📘 ROTEIRO – SOM ALTO / PERTURBAÇÃO DO SOSSEGO (190)</h2>

      <div id="step-1" class="roteiro-step">
        <h3>Passo 1: Identificação da Ocorrência</h3>
        <blockquote>“Centro de Atendimento: Qual é a sua emergência?”</blockquote>
        ${qSimNao("Está acontecendo agora?")}
        ${qTexto("É residência, bar ou via pública?")}
        ${qSimNao("É som automotivo?")}
        ${qSimNao("Já tentou conversar?")}
        ${qSimNao("Há ameaça, briga ou agressividade no local?")}

        <div class="step-navigation">
          <button class="btn-step" onclick="mudarPasso(2)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-2" class="roteiro-step" style="display: none;">
        <h3>Passo 2: Classificação Operacional</h3>
        <p><strong>🟡 ATENÇÃO - Som alto EM ANDAMENTO (sem agressividade):</strong></p>
        <ul style="font-size: 14px;">
          <li>Criar chamada, prioridade normal.</li>
          <li>NÃO marcar “Alerta” (salvo se houver risco).</li>
        </ul>

        <p><strong>🔴 CRÍTICO - Som alto com ameaça / briga:</strong></p>
        <ul style="font-size: 14px;">
          <li>Avaliar marcação de “Alerta” e Elevar prioridade urgente.</li>
        </ul>

        <p><strong>🟢 INFORMAÇÃO - Som já encerrado:</strong></p>
        <ul style="font-size: 14px;">
          <li>Não criar chamada se não houver fato em andamento. Orientar que acione novamente caso retorne.</li>
        </ul>

        <div class="step-navigation" style="justify-content: space-between;">
          <button class="btn-step btn-back" onclick="mudarPasso(1)">⬅️ Voltar</button>
          <button class="btn-step" onclick="mudarPasso(3)">Avançar ➡️</button>
        </div>
      </div>

      <div id="step-3" class="roteiro-step" style="display: none;">
        <h3>Passo 3: Anonimato e Cuidados</h3>
        <p style="font-size: 14px;"><strong>Se pedir anonimato:</strong> Registrar como “Anônimo” e Inserir no histórico: PRESERVAR O ANONIMATO DO SOLICITANTE.</p>
        
        <ul style="color: #b91c1c; font-size: 14px; margin-top: 10px;">
          <li>❌ Não tomar partido (“vizinho sempre faz isso”).</li>
          <li>❌ Não prometer horário de chegada.</li>
          <li>❌ Não dizer que a viatura “já está indo” se não estiver empenhada.</li>
        </ul>

        <h3>Passo 4: Encerramento</h3>
        <blockquote>“SENHOR(A), SUA SOLICITAÇÃO JÁ ESTÁ REGISTRADA. O ATENDIMENTO OCORRE CONFORME O GRAU DE PRIORIDADE.”</blockquote>
        <div class="step-navigation" style="justify-content: flex-start;">
          <button class="btn-step btn-back" onclick="mudarPasso(2)">⬅️ Voltar</button>
        </div>
      </div>
    `
  }
];