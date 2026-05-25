// =====================================================
//  COBOM 193 – Sistema de Triagem Operacional
//  Versão Atualizada 2026 – Telas Agrupadas & Botão Todos
// =====================================================

const stepCategoria = document.getElementById("stepCategoria");
const stepNatureza = document.getElementById("stepNatureza");
const stepRoteiro = document.getElementById("stepRoteiro");

const abordagemInicial = document.getElementById("abordagemInicial");
const tituloPassoRoteiro = document.getElementById("tituloPassoRoteiro");
const conteudoDinamico = document.getElementById("conteudoDinamico");

const inputBuscaNatureza = document.getElementById("inputBuscaNatureza");
const listaSugestoes = document.getElementById("listaSugestoes");
const codigoNaturezaSelecionada = document.getElementById("codigoNaturezaSelecionada");

const blocoNotas = document.getElementById("blocoNotas");
const painelNavegacao = document.getElementById("painelNavegacao");
const btnVoltar = document.getElementById("btnVoltar");
const btnAvancar = document.getElementById("btnAvancar");
const btnNovaOcorrencia = document.getElementById("btnNovaOcorrencia");

// Controle de Passos: 0=Categoria, 1=Busca Natureza, 2=Perguntas, 3=Orientações/CAD/Supervisor
let passoAtual = 0; 
let roteirosFiltradosAtuais = [];
let dadosRoteiro = { titulo: "", perguntas: [], cad: [], supervisor: [], orientacoes: [] };

// ================= MÁQUINA DE ESTADOS (WIZARD) =================

function atualizarNavegacao() {
    stepCategoria.style.display = "none";
    stepNatureza.style.display = "none";
    stepRoteiro.style.display = "none";
    painelNavegacao.style.display = "none";
    btnAvancar.style.display = "none";
    btnNovaOcorrencia.style.display = "none";
    btnVoltar.style.display = "block";

    if (passoAtual === 0) {
        stepCategoria.style.display = "block";
        
    } else if (passoAtual === 1) {
        stepCategoria.style.display = "block"; 
        stepNatureza.style.display = "block";
        painelNavegacao.style.display = "flex";
        btnAvancar.style.display = "block";
        btnAvancar.textContent = "Confirmar Natureza";

    } else if (passoAtual >= 2) {
        stepRoteiro.style.display = "block";
        painelNavegacao.style.display = "flex";
        
        if (passoAtual === 3) {
            btnNovaOcorrencia.style.display = "block";
            btnAvancar.style.display = "none";
        } else {
            btnAvancar.style.display = "block";
            btnAvancar.textContent = "Avançar →";
        }
        
        renderizarConteudoRoteiro(); 
    }
}

// ================= NAVEGAÇÃO =================

btnAvancar.onclick = () => {
    if (passoAtual === 1) {
        if (!codigoNaturezaSelecionada.value || !inputBuscaNatureza.value) {
            alert("Por favor, pesquise e clique numa natureza da lista antes de continuar.");
            inputBuscaNatureza.focus();
            return;
        }
        extrairDadosDoRoteiroSelecionado(codigoNaturezaSelecionada.value);
    }
    
    if (passoAtual < 3) {
        passoAtual++;
        atualizarNavegacao();
    }
};

btnVoltar.onclick = () => {
    if (passoAtual > 0) {
        passoAtual--;
        atualizarNavegacao();
    }
};

btnNovaOcorrencia.onclick = () => {
    passoAtual = 0;
    blocoNotas.value = ""; 
    document.querySelectorAll("#botoesFiltro button").forEach(btn => {
        btn.classList.remove("btn-next");
        btn.classList.add("btn-back");
    });
    atualizarNavegacao();
};

// ================= FILTRO E AUTOCOMPLETE =================

function filtrarNaturezas(codigoLetra, btnClicado) {
    document.querySelectorAll("#botoesFiltro button").forEach(btn => {
        btn.classList.remove("btn-next");
        btn.classList.add("btn-back");
    });
    btnClicado.classList.remove("btn-back");
    btnClicado.classList.add("btn-next");

    inputBuscaNatureza.value = "";
    codigoNaturezaSelecionada.value = "";
    listaSugestoes.style.display = "none";
    
    if (codigoLetra === 'TODOS') {
        roteirosFiltradosAtuais = [...ROTEIROS_DATA]; // Copia todo o array sem aplicar nenhum filtro
    } else if (codigoLetra === 'OUTROS') {
        roteirosFiltradosAtuais = ROTEIROS_DATA.filter(r => !/^[OVSR]/.test(r.codigo));
    } else {
        roteirosFiltradosAtuais = ROTEIROS_DATA.filter(r => r.codigo.startsWith(codigoLetra));
    }

    roteirosFiltradosAtuais.sort((a, b) => a.titulo.localeCompare(b.titulo));
    
    passoAtual = 1;
    atualizarNavegacao();
}

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
            codigoNaturezaSelecionada.value = roteiro.codigo;
            listaSugestoes.style.display = "none";
        };
        
        listaSugestoes.appendChild(li);
    });
    listaSugestoes.style.display = "block";
}

// ================= EXTRAÇÃO E RENDERIZAÇÃO =================

function extrairDadosDoRoteiroSelecionado(codigo) {
    const roteiro = ROTEIROS_DATA.find(r => r.codigo === codigo);
    if (!roteiro) return;

    blocoNotas.value = "";
    registrar("NATUREZA", roteiro.titulo);
    dadosRoteiro.titulo = roteiro.titulo;

    const parser = new DOMParser();
    const doc = parser.parseFromString(roteiro.roteiro, "text/html");
    const html = doc.body.innerHTML;

    dadosRoteiro.perguntas = extrairSecao(html, "PERGUNTAS")
        .filter(p => {
            const norm = p.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            return !/^qual (e |o )endereco/.test(norm);
        });
    dadosRoteiro.cad = extrairSecao(html, "REGISTRO NO CAD");
    dadosRoteiro.supervisor = extrairSecao(html, "ACIONAR SUPERVISOR BM");
    dadosRoteiro.orientacoes = extrairSecao(html, "ORIENTAÇÕES");
}

function extrairSecao(html, titulo) {
    const regex = new RegExp(`<strong>${titulo}<\\/strong>([\\s\\S]*?)(?=<strong>|$)`);
    const match = html.match(regex);
    if (!match) return [];
    
    return match[1]
        .replace(/\r/g, "")
        .split("\n")
        .map(l => l.replace(/<[^>]*>/g, "").trim())
        .filter(l => l.startsWith("-"))
        .map(l => l.replace(/^- /, ""));
}

function renderizarConteudoRoteiro() {
    conteudoDinamico.innerHTML = "";
    abordagemInicial.style.display = "none";

    if (passoAtual === 2) {
        tituloPassoRoteiro.textContent = "🧠 PERGUNTAS OPERACIONAIS";
        abordagemInicial.style.display = "block"; 
        
        dadosRoteiro.perguntas.forEach(p => {
            conteudoDinamico.appendChild(criarInputTexto(p));
        });
        
    } else if (passoAtual === 3) {
        tituloPassoRoteiro.textContent = "✅ CONCLUSÃO E DESPACHO";
        
        // 1. Orientações
        const divOrienta = document.createElement("div");
        divOrienta.style.marginBottom = "30px";
        divOrienta.innerHTML = `<h4 style="color: #10b981; margin-bottom: 10px; font-family: Montserrat;">🟢 ORIENTAÇÕES AO SOLICITANTE</h4>`;
        divOrienta.appendChild(renderListaSimples(dadosRoteiro.orientacoes));
        conteudoDinamico.appendChild(divOrienta);

        // 2. Registro no CAD
        const divCad = document.createElement("div");
        divCad.style.marginBottom = "30px";
        divCad.innerHTML = `<h4 style="color: #f59e0b; margin-bottom: 10px; font-family: Montserrat;">📝 REGISTRO NO CAD</h4>`;
        divCad.appendChild(renderListaSimples(dadosRoteiro.cad));
        conteudoDinamico.appendChild(divCad);

        // 3. Supervisor BM
        const divSup = document.createElement("div");
        divSup.innerHTML = `<h4 style="color: #ef4444; margin-bottom: 10px; font-family: Montserrat;">🚨 ACIONAR SUPERVISOR BM</h4>`;
        divSup.appendChild(renderListaSimples(dadosRoteiro.supervisor));
        conteudoDinamico.appendChild(divSup);
    }
}

// ================= COMPONENTES VISUAIS =================

function renderListaSimples(itens) {
    const ul = document.createElement("ul");
    ul.style.paddingLeft = "20px";
    ul.style.color = "#475569";
    ul.style.fontSize = "1.05rem";

    if (itens.length === 0) {
        const li = document.createElement("li");
        li.textContent = "Nenhuma instrução específica para esta etapa no protocolo.";
        ul.appendChild(li);
        return ul;
    }

    itens.forEach(i => {
        const li = document.createElement("li");
        li.style.marginBottom = "10px";
        li.textContent = i;
        ul.appendChild(li);
    });
    return ul;
}

function criarInputTexto(pergunta) {
    const div = document.createElement("div");
    div.className = "interactive-q";
    div.innerHTML = `
        <span class="q-text">${pergunta}</span>
        <div class="q-actions">
            <input type="text" class="q-input" placeholder="Digite e pressione Enter">
            <button class="btn-ok">OK</button>
        </div>
    `;

    const input = div.querySelector(".q-input");
    
    const salvar = () => {
        if (input.value.trim()) {
            registrar(pergunta, input.value);
            input.value = "";
            input.placeholder = "✓ Registrado";
        }
    };

    div.querySelector(".btn-ok").onclick = e => {
        e.preventDefault();
        salvar();
    };

    input.onkeydown = e => {
        if (e.key === "Enter") {
            e.preventDefault();
            salvar();
        }
    };

    return div;
}

// ================= UTILITÁRIOS =================

function registrar(pergunta, resposta) {
    blocoNotas.value += `${pergunta} R: ${resposta}\n`;
    blocoNotas.scrollTop = blocoNotas.scrollHeight;
}

function copiarNotas() {
    blocoNotas.select();
    document.execCommand("copy");
}

// Inicializa a navegação
atualizarNavegacao();