// ==========================================
// ÁRVORE DE DECISÃO E LÓGICA DE NAVEGAÇÃO
// ==========================================

const tree = {
    1: {
        q: "CENTO E NOVENTA E SETE. QUAL É SUA OCORRÊNCIA?",
        g: "Identifique se o fato é uma emergência criminal ou solicitação de serviço.",
        o: [
            { text: "Óbito / Cadáver", n: 10 },
            { text: "Crime já ocorrido", n: 20 },
            { text: "Pessoa desaparecida", n: 30 },
            { text: "Mandado de prisão", n: 40 },
            { text: "Informações / Outros", n: 300 }
        ]
    },
    10: {
        q: "O óbito ocorreu em hospital ou em via pública/residência?",
        g: "Define se o acionamento é para remoção hospitalar ou perícia de local.",
        o: [
            { text: "Hospital / Unidade de Saúde", n: 11 },
            { text: "Local de Crime / Acidente", n: 12 }
        ]
    },
    11: { 
        q: "Existe relatório médico ou CRM do óbito?", 
        g: "Documento obrigatório para remoção pelo rabecão.", 
        o: [{ text: "Sim", n: 150 }, { text: "Não", n: 150 }] 
    },
    12: { 
        q: "O local está isolado e preservado?", 
        g: "Oriente a não tocar no corpo ou objetos até a chegada da perícia.", 
        o: [{ text: "Sim", n: 150 }, { text: "Não", n: 150 }] 
    },
    20: {
        q: "O crime aconteceu agora ou faz tempo?",
        g: "Se em flagrante, o despacho deve ser prioritário.",
        o: [
            { text: "Ocorrendo Agora (Urgência)", n: 150 },
            { text: "Fato Passado", n: 200 }
        ]
    },
    150: { q: "PROCEDIMENTO: GERAR CHAMADA", g: "Preencha os dados no sistema e despache para a unidade operacional.", o: [] },
    200: { q: "ORIENTAÇÃO: DELEGACIA VIRTUAL", g: "Incentive o registro via site ou na delegacia da área.", o: [] },
    300: { q: "INFORMAÇÕES GERAIS", g: "Forneça o endereço da unidade policial mais próxima ou ramal interno.", o: [] }
};

let stack = [], cur = 1;
const questionEl = document.getElementById("question");
const guidanceEl = document.getElementById("guidance");
const optionsEl = document.getElementById("options");
const notepadEl = document.getElementById("notepad");

function render() {
    const node = tree[cur];
    questionEl.innerText = node.q;
    guidanceEl.innerText = node.g;
    optionsEl.innerHTML = "";

    node.o.forEach(opt => {
        let btn = document.createElement("button");
        btn.className = "btn btn-option";
        btn.innerText = opt.text;
        btn.onclick = () => {
            notepadEl.value += "P: " + node.q + "\nR: " + opt.text + "\n\n";
            stack.push(cur);
            cur = opt.n;
            render();
            notepadEl.scrollTop = notepadEl.scrollHeight;
        };
        optionsEl.appendChild(btn);
    });
}

function back() {
    if (stack.length) {
        cur = stack.pop();
        // Remove a última entrada do bloco de notas (simplificado)
        let lines = notepadEl.value.trim().split("\n\n");
        lines.pop();
        notepadEl.value = lines.length > 0 ? lines.join("\n\n") + "\n\n" : "";
        render();
    }
}

function showSection(id) {
    document.querySelectorAll(".dynamic-content").forEach(s => s.classList.add("div-invisivel"));
    const sec = document.getElementById(id);
    if (sec) sec.classList.remove("div-invisivel");

    document.querySelectorAll("#main-menu .nav-link").forEach(l => l.classList.remove("active"));
    const btn = document.querySelector(`.nav-link[onclick="showSection('${id}')"]`);
    if (btn) btn.classList.add("active");
}

function copyNotes() {
    notepadEl.select();
    document.execCommand("copy");
    alert("Texto copiado para a área de transferência!");
}

function clearNotes() {
    if (confirm("Deseja limpar todo o histórico do atendimento?")) {
        notepadEl.value = "";
        cur = 1;
        stack = [];
        render();
    }
}

window.onload = () => {
    render();
    showSection('triagem');
};