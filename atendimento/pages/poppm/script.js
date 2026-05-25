 const treeData = {

/* ===================== */
/* NÓ INICIAL */
/* ===================== */

1: {
    question: "Qual é a situação principal informada pelo solicitante?",
    guidance: "<strong>Objetivo:</strong> Identificar se há indício de crime, contravenção ou apenas incômodo.<br><br><strong>Perguntas-chave:</strong><ul><li>O que exatamente está acontecendo agora?</li><li>Isso envolve violência, ameaça, drogas ou apenas barulho/incômodo?</li></ul>",
    options: [
        { text: "Crime contra patrimônio", next: 2 },
        { text: "Violência contra pessoa", next: 10 },
        { text: "Drogas ou atividade suspeita organizada", next: 20 },
        { text: "Crime sexual", next: 30 },
        { text: "Som alto / perturbação do sossego", next: 50 },
        { text: "Outro tipo de ocorrência", next: 99 }
    ]
},

/* ===================== */
/* CRIMES PATRIMONIAIS */
/* ===================== */

2: {
    question: "Houve uso de violência ou ameaça contra a vítima?",
    guidance: "<strong>Diferencia Furto x Roubo</strong>",
    options: [
        { text: "Não houve violência ou ameaça", next: 3 },
        { text: "Houve ameaça ou violência", next: 4 }
    ]
},

3: {
    question: "A subtração ocorreu sem contato direto com a vítima?",
    guidance: "Exemplos: celular furtado, residência arrombada, objeto retirado sem abordagem.",
    options: [
        { text: "Sim, sem contato com a vítima", next: "RESULT_1" },
        { text: "Tentativa percebida pela vítima", next: "RESULT_1" }
    ]
},

4: {
    question: "Houve contato direto com a vítima durante a subtração?",
    guidance: "Inclui ameaça, empurrão, intimidação ou uso de arma.",
    options: [
        { text: "Sim", next: "RESULT_2" }
    ]
},

/* ===================== */
/* VIOLÊNCIA CONTRA PESSOA */
/* ===================== */

10: {
    question: "A violência ocorreu no ambiente familiar ou entre pessoas com vínculo?",
    guidance: "Considerar cônjuge, ex-cônjuge, companheiro(a), parentes ou conviventes.",
    options: [
        { text: "Sim", next: 11 },
        { text: "Não", next: 14 }
    ]
},

11: {
    question: "A vítima é mulher e o agressor possui vínculo íntimo ou familiar?",
    guidance: "Aplicação da Lei Maria da Penha.",
    options: [
        { text: "Sim", next: "RESULT_4" },
        { text: "Não", next: 12 }
    ]
},

12: {
    question: "A vítima é criança ou adolescente?",
    guidance: "",
    options: [
        { text: "Sim", next: "RESULT_8" },
        { text: "Não", next: 14 }
    ]
},

14: {
    question: "Houve morte da vítima?",
    guidance: "",
    options: [
        { text: "Sim", next: 15 },
        { text: "Não, apenas agressão/ameaça", next: 99 }
    ]
},

15: {
    question: "A vítima é mulher e o crime envolve contexto de gênero?",
    guidance: "Ciúmes, relação afetiva, menosprezo ou discriminação por condição feminina.",
    options: [
        { text: "Sim", next: "RESULT_6" },
        { text: "Não", next: "RESULT_5" }
    ]
},

/* ===================== */
/* DROGAS / CRIME ORGANIZADO */
/* ===================== */

20: {
    question: "Há indícios de comércio, transporte ou distribuição de drogas?",
    guidance: "Venda, ponto de tráfico, grande quantidade ou logística organizada.",
    options: [
        { text: "Sim", next: "RESULT_3" },
        { text: "Não", next: 21 }
    ]
},

21: {
    question: "Há atuação de grupo organizado ou facção criminosa?",
    guidance: "Extorsão, domínio territorial, armas, organização criminosa.",
    options: [
        { text: "Sim", next: "RESULT_9" },
        { text: "Não", next: 99 }
    ]
},

/* ===================== */
/* CRIMES SEXUAIS */
/* ===================== */

30: {
    question: "O fato envolve constrangimento sexual ou ato sem consentimento?",
    guidance: "",
    options: [
        { text: "Sim", next: 31 },
        { text: "Não", next: 99 }
    ]
},

31: {
    question: "A vítima é criança ou adolescente?",
    guidance: "",
    options: [
        { text: "Sim", next: "RESULT_8" },
        { text: "Não", next: "RESULT_7" }
    ]
},

/* ===================== */
/* PERTURBAÇÃO DO SOSSEGO */
/* ===================== */

50: {
    question: "O incômodo relatado é causado por som alto, festa, gritaria ou barulho contínuo?",
    guidance: "Exemplos: som automotivo, festa em residência, barulho excessivo.",
    options: [
        { text: "Sim", next: 51 },
        { text: "Não, é outro tipo de incômodo", next: 99 }
    ]
},

51: {
    question: "O barulho está ocorrendo neste momento?",
    guidance: "Verificar situação de flagrante.",
    options: [
        { text: "Sim, está acontecendo agora", next: 52 },
        { text: "Não, já cessou", next: 55 }
    ]
},

52: {
    question: "O barulho é recorrente ou já causou conflito entre vizinhos?",
    guidance: "Avaliar risco de escalada para violência.",
    options: [
        { text: "Sim, é recorrente ou já houve discussão", next: 53 },
        { text: "Não, é um evento pontual", next: 54 }
    ]
},

53: {
    question: "Há ameaça, agressão ou risco de violência no local?",
    guidance: "Se houver violência, sair do fluxo de sossego.",
    options: [
        { text: "Sim", next: 10 },
        { text: "Não", next: "RESULT_50" }
    ]
},

54: {
    question: "O solicitante deseja apenas orientação ou intervenção policial?",
    guidance: "",
    options: [
        { text: "Intervenção policial no local", next: "RESULT_50" },
        { text: "Apenas orientação", next: "RESULT_51" }
    ]
},

55: {
    question: "O solicitante deseja registrar o fato formalmente?",
    guidance: "Sem flagrante, pode não haver deslocamento.",
    options: [
        { text: "Sim", next: "RESULT_52" },
        { text: "Não", next: "RESULT_51" }
    ]
},

/* ===================== */
/* RESULTADOS */
/* ===================== */

RESULT_1: { result: "Top 1 – Furto" },
RESULT_2: { result: "Top 2 – Roubo" },
RESULT_3: { result: "Top 3 – Tráfico de Drogas" },
RESULT_4: { result: "Top 4 – Violência Doméstica" },
RESULT_5: { result: "Top 5 – Homicídio" },
RESULT_6: { result: "Top 6 – Feminicídio" },
RESULT_7: { result: "Top 7 – Crimes Sexuais" },
RESULT_8: { result: "Top 8 – Crimes contra Criança/Adolescente" },
RESULT_9: { result: "Top 9 – Crime Organizado / Facções" },

RESULT_50: {
    result: "Perturbação do Sossego (Contravenção Penal – Art. 42 LCP) – Enviar guarnição se disponível"
},

RESULT_51: {
    result: "Orientação ao solicitante – Sem envio de viatura"
},

RESULT_52: {
    result: "Perturbação do Sossego sem flagrante – Orientar registro posterior"
},

99: {
    result: "Fora do Top 10 ou informações insuficientes – Encaminhar para triagem complementar"
}

};
