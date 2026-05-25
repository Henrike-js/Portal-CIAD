// =====================================================================
// LÓGICA DE INTERFACE E PARSER DE JSON (197 - BALÕES DE PESQUISA)
// =====================================================================

const stepMenu = document.getElementById("stepMenu");
const menuGrid = document.getElementById("menuGrid");
const inputBuscaNatureza = document.getElementById("inputBuscaNatureza");

const stepRoteiro = document.getElementById("stepRoteiro");
const conteudoDinamico = document.getElementById("conteudoDinamico");
const tituloRoteiro = document.getElementById("tituloRoteiro");
const descricaoRoteiro = document.getElementById("descricaoRoteiro");
const blocoNotas = document.getElementById("blocoNotas");

// =====================================================================
// RENDERIZADOR DO MENU (BALÕES / PILLS)
// =====================================================================

// Função que desenha os balões. Recebe a lista filtrada ou a completa.
function renderizarMenu(lista = ROTEIROS_DATA) {
  menuGrid.innerHTML = "";
  
  // Ajusta o layout do container para alinhar os balões lado a lado
  menuGrid.classList.remove("cards-grid");
  menuGrid.style.display = "flex";
  menuGrid.style.flexWrap = "wrap";
  menuGrid.style.gap = "12px";
  
  if (!lista || lista.length === 0) {
    menuGrid.innerHTML = "<p style='color: #64748b;'>Nenhuma ocorrência encontrada com este nome.</p>";
    return;
  }

  lista.forEach(roteiro => {
    // Cria o balão
    const balao = document.createElement("button");
    balao.className = "btn-step btn-back";
    
    // Estilos inline para forçar o formato arredondado de balão
    balao.style.borderRadius = "999px";
    balao.style.padding = "10px 20px";
    balao.style.fontWeight = "700";
    balao.style.fontSize = "0.95rem";
    balao.style.margin = "0";
    
    // Insere apenas o Título
    balao.textContent = roteiro.TÍTULO;

    // Ao clicar no balão, abre o roteiro correspondente
    balao.onclick = () => confirmarNatureza(roteiro.NATUREZA);
    
    menuGrid.appendChild(balao);
  });
}

// =====================================================================
// BARRA DE PESQUISA (FILTRA OS BALÕES EM TEMPO REAL)
// =====================================================================

inputBuscaNatureza.addEventListener("input", function() {
  const termo = this.value.toUpperCase();
  
  // Filtra o JSON pelo Título ou pelo Código da Natureza
  const matches = ROTEIROS_DATA.filter(r => 
    r.TÍTULO.toUpperCase().includes(termo) || 
    r.NATUREZA.toUpperCase().includes(termo)
  );
  
  // Redesenha os balões apenas com os resultados da pesquisa
  renderizarMenu(matches);
});

inputBuscaNatureza.addEventListener("focus", function() {
    this.style.borderColor = "#2f5dff";
});
inputBuscaNatureza.addEventListener("blur", function() {
    this.style.borderColor = "#cbd5e1";
});

// =====================================================================
// CONSTRUTOR DINÂMICO DE ROTEIRO A PARTIR DE TEXTO PLANO
// =====================================================================

window.confirmarNatureza = function(codigo) {
  const roteiro = ROTEIROS_DATA.find(r => r.NATUREZA === codigo);
  if(!roteiro) return;

  // Atualiza Cabeçalho do Roteiro
  tituloRoteiro.textContent = `${roteiro.NATUREZA} - ${roteiro.TÍTULO}`;
  descricaoRoteiro.textContent = roteiro.DESCRIÇÃO;
  
  blocoNotas.value = "";
  enviarParaBloco("NATUREZA", roteiro.TÍTULO);

  // Troca de Telas (Esconde Menu, Mostra Roteiro)
  stepMenu.style.display = "none";
  stepRoteiro.style.display = "block";

  let html = "";

  // -----------------------------------------------------------------
  // PASSO 1: Ações e Triagem
  // -----------------------------------------------------------------
  html += `
    <div id="step-1" class="roteiro-step">
      <h3 style="color: #2f5dff; margin-bottom: 15px; font-family: Montserrat;">🧠 AÇÕES E PERGUNTAS (Triagem)</h3>
  `;

  // Palavras-chave que identificam perguntas de endereço a serem ocultadas
  const PALAVRAS_ENDERECO = [
    "endereço", "endereco", "logradouro", "rua", "avenida", "av.",
    "número", "numero", "bairro", "cep", "complemento", "referência",
    "referencia", "localização", "localizacao", "local do fato",
    "local da ocorrência", "local da ocorrencia"
  ];

  function isEnderecoQ(texto) {
    const lower = texto.toLowerCase();
    return PALAVRAS_ENDERECO.some(p => lower.includes(p));
  }

  const acoesArray = roteiro.AÇÕES.split(';')
    .map(acao => acao.replace(/^[a-z]\)\s*/i, '').trim())
    .filter(acao => acao.length > 0)
    .filter(acao => !isEnderecoQ(acao)); // 🚫 Remove perguntas de endereço

  acoesArray.forEach(acaoTexto => {
    
    // 1. Se encontrar a palavra "saudação padrão", cria o bloco visual destacado
    if (acaoTexto.toLowerCase().includes("saudação padrão")) {
      html += `
        <div style="margin-bottom: 25px;">
          <h4 style="font-size: 1.05rem; color: #6b7280; margin-bottom: 10px; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px; font-family: Montserrat;">
            Passo 1: Abordagem Inicial
          </h4>
          <div style="background-color: #f0f4ff; border-left: 5px solid #2f5dff; padding: 20px; border-radius: 4px;">
            <p style="font-style: italic; color: #1e3a8a; font-weight: 700; font-size: 1.1rem; text-transform: uppercase; margin: 0;">
              "Centro de Atendimento: Qual é a sua emergência?"
            </p>
          </div>
          <p style="margin-top: 10px; color: #6b7280; font-size: 0.85rem;">
            Persistindo sem resposta → qualificar como Falha na Comunicação.
          </p>
        </div>
      `;
    } 
    // 2. Se tiver a tag (S/N), gera botões Sim/Não interactivos
    else if (acaoTexto.includes("(S/N)")) {
      let perguntaLimpa = acaoTexto.replace("(S/N)", "").trim();
      html += qSimNao(perguntaLimpa);
    } 
    // 3. Caso contrário, gera uma caixa de texto padrão
    else {
      html += qTexto(acaoTexto, "Registrar informação aqui...");
    }
  });

  html += `
      <div class="step-navigation">
        <button class="btn-step btn-next" onclick="mudarPasso(2)">Avançar ➡️</button>
      </div>
    </div> `;

  // -----------------------------------------------------------------
  // PASSO 2: Procedimentos no Local
  // -----------------------------------------------------------------
  html += `
    <div id="step-2" class="roteiro-step" style="display: none;">
      <h3 style="color: #f59e0b; margin-top: 10px; margin-bottom: 15px; font-family: Montserrat;">🚨 PROCEDIMENTOS NO LOCAL</h3>
      <ul style="color: #475569; font-size: 1.05rem; padding-left: 20px;">
  `;
  
  const procedimentosArray = roteiro["ATENDIMENTO LOCAL"].split(';')
    .map(proc => proc.replace(/^[a-z]\)\s*/i, '').trim())
    .filter(proc => proc.length > 0);
    
  procedimentosArray.forEach(proc => {
    html += `<li style="margin-bottom: 8px;">${proc}</li>`;
  });
  
  html += `
      </ul>
      <div class="step-navigation" style="justify-content: space-between;">
        <button class="btn-step btn-back" onclick="mudarPasso(1)">⬅️ Voltar</button>
        <button class="btn-step btn-next" onclick="mudarPasso(3)">Avançar ➡️</button>
      </div>
    </div> `;

  // -----------------------------------------------------------------
  // PASSO 3: Encerramento
  // -----------------------------------------------------------------
  html += `
    <div id="step-3" class="roteiro-step" style="display: none;">
      <h3 style="color: #10b981; margin-top: 10px; margin-bottom: 15px; font-family: Montserrat;">✅ CONCLUSÃO DA CHAMADA</h3>
      
      <div style="background-color: #f0fdf4; border-left: 5px solid #10b981; padding: 20px; border-radius: 4px;">
        <p style="margin: 0; color: #065f46; font-size: 1.1rem;"><strong>Local de Encerramento:</strong><br> ${roteiro["LOCAL DE ENCERRAMENTO"]}</p>
      </div>

      <div class="step-navigation" style="justify-content: flex-start;">
        <button class="btn-step btn-back" onclick="mudarPasso(2)">⬅️ Voltar</button>
      </div>
    </div> `;

  conteudoDinamico.innerHTML = html;
};

// =====================================================================
// NAVEGAÇÃO DOS PASSOS (WIZARD)
// =====================================================================

window.mudarPasso = function(passoDestino) {
  const passos = document.querySelectorAll('.roteiro-step');
  passos.forEach(el => el.style.display = 'none'); 
  
  const proximoPasso = document.getElementById('step-' + passoDestino);
  if (proximoPasso) {
    proximoPasso.style.display = 'block'; 
  }
};

// =====================================================================
// FUNÇÕES GERADORAS DE COMPONENTES E BLOCO DE NOTAS
// =====================================================================

function qTexto(pergunta, placeholder) {
  return `
    <div class="interactive-q">
      <span class="q-text">${pergunta}</span>
      <div class="q-actions">
        <input type="text" class="q-input" placeholder="${placeholder}" onkeypress="if(event.key === 'Enter') addRespostaTexto(this.nextElementSibling)">
        <button class="btn-ok" onclick="addRespostaTexto(this)">OK</button>
      </div>
    </div>
  `;
}

function qSimNao(pergunta) {
  return `
    <div class="interactive-q">
      <span class="q-text">${pergunta}</span>
      <div class="q-actions">
        <button class="btn-sn sim" onclick="addResposta(this, 'Sim')">Sim</button>
        <button class="btn-sn nao" onclick="addResposta(this, 'Não')">Não</button>
      </div>
    </div>
  `;
}

function enviarParaBloco(pergunta, resposta) {
  let textoAtual = blocoNotas.value;
  if (textoAtual.length > 0 && !textoAtual.endsWith('\n')) {
    textoAtual += '\n';
  }
  blocoNotas.value = textoAtual + pergunta + " R: " + resposta + "\n\n";
  blocoNotas.scrollTop = blocoNotas.scrollHeight;
}

window.addResposta = function(btn, resposta) {
  const container = btn.closest('.interactive-q');
  const pergunta = container.querySelector('.q-text').innerText;
  
  enviarParaBloco(pergunta, resposta);
  
  const textoOriginal = btn.innerText;
  btn.innerText = '✔️';
  setTimeout(() => { btn.innerText = textoOriginal; }, 1000);
};

window.addRespostaTexto = function(btn) {
  const container = btn.closest('.interactive-q');
  const pergunta = container.querySelector('.q-text').innerText;
  const input = container.querySelector('.q-input');
  const resposta = input.value.trim();

  if (resposta) {
    enviarParaBloco(pergunta, resposta);
    
    const bgOriginal = btn.style.backgroundColor;
    btn.innerText = '✔️ Salvo';
    btn.style.backgroundColor = '#10b981'; 
    input.value = ''; 
    
    setTimeout(() => { 
      btn.innerText = 'OK'; 
      btn.style.backgroundColor = bgOriginal; 
    }, 1500);
  } else {
    input.focus(); 
  }
};

window.resetarSistema = function() {
  stepRoteiro.style.display = "none";
  stepMenu.style.display = "block"; 
  blocoNotas.value = "";
  
  // Limpa a pesquisa e volta a mostrar todos os balões
  inputBuscaNatureza.value = "";
  renderizarMenu();
};

// Botão Copiar Notas (Clipboard)
const btnCopiarNotas = document.getElementById('btnCopiarNotas');
if (btnCopiarNotas) {
  btnCopiarNotas.addEventListener('click', () => {
    const textoParaCopiar = blocoNotas.value;
    if (!textoParaCopiar.trim()) return;
    
    navigator.clipboard.writeText(textoParaCopiar).then(() => {
      const conteudoOriginal = btnCopiarNotas.innerHTML;
      btnCopiarNotas.innerHTML = `✓ Copiado!`;
      btnCopiarNotas.classList.add('sucesso');
      setTimeout(() => {
        btnCopiarNotas.innerHTML = conteudoOriginal;
        btnCopiarNotas.classList.remove('sucesso');
      }, 2000);
    });
  });
}

// =====================================================================
// INICIALIZAÇÃO
// =====================================================================
// Quando a página abre, desenha todos os balões
renderizarMenu();