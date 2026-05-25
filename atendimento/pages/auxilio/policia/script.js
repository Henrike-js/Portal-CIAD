// =====================================================================
// LÓGICA DE INTERFACE E CONTROLE DO SISTEMA (190)
// =====================================================================

// Elementos de Interface
const stepCategoria = document.getElementById("stepCategoria");
const stepNatureza = document.getElementById("stepNatureza");
const stepRoteiro = document.getElementById("stepRoteiro");
const conteudoDinamico = document.getElementById("conteudoDinamico");

const inputBuscaNatureza = document.getElementById("inputBuscaNatureza");
const listaSugestoes = document.getElementById("listaSugestoes");
const codigoNaturezaSelecionada = document.getElementById("codigoNaturezaSelecionada");
const blocoNotas = document.getElementById("blocoNotas");

let roteirosFiltradosAtuais = [];

// =====================================================================
// FUNÇÕES GERADORAS DE COMPONENTES INTERATIVOS
// =====================================================================
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

function qTexto(pergunta, placeholder = "Digite a resposta aqui...") {
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

// =====================================================================
// LÓGICA DE CAPTURA E BLOCO DE NOTAS
// =====================================================================
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
    btn.innerText = '✔️ Registrado';
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

window.handleFurtoVeiculo = function(btn, isSim) {
  const containerVeiculo = document.getElementById('perguntas-veiculo');
  if (isSim) {
    containerVeiculo.style.display = 'block';
    enviarParaBloco("Foi furto de veículo?", "Sim");
  } else {
    containerVeiculo.style.display = 'none';
    enviarParaBloco("Foi furto de veículo?", "Não");
  }
  const textoOriginal = btn.innerText;
  btn.innerText = '✔️';
  setTimeout(() => { btn.innerText = textoOriginal; }, 1000);
};

// =====================================================================
// NAVEGAÇÃO ENTRE PASSOS NO ROTEIRO ESPECÍFICO
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
// FILTRO, PESQUISA E WIZARD (DASHBOARD)
// =====================================================================
window.filtrarNaturezas = function(categoria, btnClicado) {
  document.querySelectorAll("#botoesFiltro button").forEach(btn => {
    btn.classList.remove("btn-next");
    btn.classList.add("btn-back");
  });
  btnClicado.classList.remove("btn-back");
  btnClicado.classList.add("btn-next");

  inputBuscaNatureza.value = "";
  codigoNaturezaSelecionada.value = "";
  listaSugestoes.style.display = "none";
  
  // A variável ROTEIROS_DATA agora vem do ficheiro roteiros_data.js
  if (categoria === 'TODOS') {
    roteirosFiltradosAtuais = [...ROTEIROS_DATA];
  } else {
    roteirosFiltradosAtuais = ROTEIROS_DATA.filter(r => r.categoria === categoria);
  }

  roteirosFiltradosAtuais.sort((a, b) => a.titulo.localeCompare(b.titulo));
  stepNatureza.style.display = "block";
};

// Auto-complete (Caixa de Pesquisa)
inputBuscaNatureza.addEventListener("input", function() {
  codigoNaturezaSelecionada.value = ""; 
  const termo = this.value.toUpperCase();
  const matches = roteirosFiltradosAtuais.filter(r => r.titulo.toUpperCase().includes(termo));
  renderizarSugestoes(matches);
});

inputBuscaNatureza.addEventListener("click", function() {
  if(!this.value) {
    renderizarSugestoes(roteirosFiltradosAtuais);
  } else {
    const matches = roteirosFiltradosAtuais.filter(r => r.titulo.toUpperCase().includes(this.value.toUpperCase()));
    renderizarSugestoes(matches);
  }
});

document.addEventListener("click", function(e) {
  if (e.target !== inputBuscaNatureza && e.target !== listaSugestoes) {
    listaSugestoes.style.display = "none";
  }
});

function renderizarSugestoes(lista) {
  listaSugestoes.innerHTML = "";
  if(lista.length === 0) {
    listaSugestoes.style.display = "none";
    return;
  }
  
  lista.forEach(roteiro => {
    const li = document.createElement("li");
    li.style.padding = "12px 15px";
    li.style.cursor = "pointer";
    li.style.borderBottom = "1px solid #f1f5f9";
    li.style.color = "#475569";
    li.style.fontFamily = "'Source Sans Pro', sans-serif";
    li.style.fontSize = "0.95rem";
    
    li.textContent = roteiro.titulo; 
    
    li.onmouseover = () => { li.style.backgroundColor = "#f0f4ff"; li.style.color = "#1e3a8a"; };
    li.onmouseout = () => { li.style.backgroundColor = "transparent"; li.style.color = "#475569"; };
    
    li.onclick = () => {
      inputBuscaNatureza.value = roteiro.titulo;
      codigoNaturezaSelecionada.value = roteiro.id;
      listaSugestoes.style.display = "none";
    };
    
    listaSugestoes.appendChild(li);
  });
  listaSugestoes.style.display = "block";
}

// Botão Confirmar Natureza
window.confirmarNatureza = function() {
  const idSelecionado = codigoNaturezaSelecionada.value;
  if (!idSelecionado) {
    alert("Por favor, pesquise e selecione uma ocorrência da lista.");
    return;
  }

  stepCategoria.style.display = "none";
  stepNatureza.style.display = "none";
  stepRoteiro.style.display = "block";
  
  const roteiroInfos = ROTEIROS_DATA.find(r => r.id === idSelecionado);
  blocoNotas.value = "";
  if(roteiroInfos) enviarParaBloco("NATUREZA SELECIONADA", roteiroInfos.titulo);

  // Aqui ele chama a função isolada que está no arquivo roteiros_data.js!
  conteudoDinamico.innerHTML = roteiroInfos.gerarHTML();
  mudarPasso(1);
};

// Botão Encerrar e Voltar para Início
window.resetarSistema = function() {
  stepRoteiro.style.display = "none";
  stepCategoria.style.display = "block";
  stepNatureza.style.display = "none";
  blocoNotas.value = "";
  
  document.querySelectorAll("#botoesFiltro button").forEach(btn => {
    btn.classList.remove("btn-next");
    btn.classList.add("btn-back");
  });
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