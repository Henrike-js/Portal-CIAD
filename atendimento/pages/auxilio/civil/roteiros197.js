// =====================================================================
// BASE DE DADOS DOS ROTEIROS DE ATENDIMENTO (197 - POLÍCIA CIVIL)
// =====================================================================

const ROTEIROS_DATA = [
  {
    "GRUPO": "C",
    "NATUREZA": "C 01.000",
    "TÍTULO": "HOMICÍDIO",
    "DESCRIÇÃO": "Ocorrência em que há relato de morte provocada por outra pessoa, mediante violência ou outro meio que resulte em óbito.",
    "AÇÕES": "a) Atender a ligação com a saudação padrão: 'Polícia Civil, 197. Qual é a sua solicitação?'; b) Solicitar nome e telefone do solicitante; c) A vítima foi identificada? (S/N); d) Colher o máximo de informações sobre o fato, como quantidade de autores, se estão armados, tipo de arma utilizada, se houve disparos, e direção de fuga; e) Há pessoas feridas no local? (S/N); f) Registrar todas as informações no sistema e gerar a chamada para despacho policial.",
    "ATENDIMENTO LOCAL": "a) A equipe policial deverá deslocar ao local imediatamente; b) Isolar e preservar o local do crime; c) Solicitar a presença da perícia e autoridade policial; d) Identificar e qualificar testemunhas; e) Levantar características dos autores e veículos envolvidos; f) Controlar fluxo de pessoas e veículos no local; g) Registrar o boletim de ocorrência.",
    "LOCAL DE ENCERRAMENTO": "Delegacia de Polícia Civil responsável pela área do fato."
  },
  {
    "GRUPO": "C",
    "NATUREZA": "C 02.000",
    "TÍTULO": "FURTO",
    "DESCRIÇÃO": "Subtração de bem ou valor sem emprego de violência ou grave ameaça à vítima.",
    "AÇÕES": "a) Atender a ligação com a saudação padrão: 'Polícia Civil, 197. Qual é a sua solicitação?'; b) O fato está acontecendo agora? (S/N); c) O autor ainda está no local? (S/N); d) O que foi furtado?; e) Há suspeito identificado? (Características, roupas); f) Existe câmera de segurança? (S/N); g) Houve arrombamento? (S/N); h) Foi furto de veículo? (S/N) (Se sim, qual a Placa, Marca, Modelo, Cor e Local exato?)",
    "ATENDIMENTO LOCAL": "a) 🔴 CRÍTICO (FURTO EM ANDAMENTO): Criar chamada, Marcar Alerta, inserir FATO EM ANDAMENTO; b) 🟡 ATENÇÃO (FURTO RECENTE): Criar chamada e registrar direção de fuga; c) 🟢 INFORMAÇÃO (FURTO PRETÉRITO): Não criar chamada para envio de recurso, orientar procurar Unidade Policial; d) Fraseologia de encerramento: 'Senhor(a), sua solicitação já está registrada. Assim que possível uma viatura será enviada ao local.'",
    "LOCAL DE ENCERRAMENTO": "Delegacia de Polícia Civil responsável pela área."
  },
  {
    "GRUPO": "C",
    "NATUREZA": "C 03.000",
    "TÍTULO": "ROUBO",
    "DESCRIÇÃO": "Subtração de bem ou valor mediante emprego de violência ou grave ameaça contra a vítima.",
    "AÇÕES": "a) Atender a ligação com a saudação padrão: 'Polícia Civil, 197. Qual é a sua solicitação?'; b) O roubo está acontecendo agora? (S/N); c) Quantos autores e como estavam vestidos?; d) Estão armados? Que tipo de arma?; e) Há vítimas feridas? (S/N); f) Veículo utilizado? (modelo, cor, placa com confirmação por palavra T de Tatu); g) Sentido da fuga?",
    "ATENDIMENTO LOCAL": "a) 🔴 CRÍTICO (ROUBO EM ANDAMENTO): Elevar prioridade imediatamente, marcar campo Alerta e inserir FATO EM ANDAMENTO; b) 🟡 ATENÇÃO (NÃO ESTÁ MAIS EM FLAGRANTE): Criar chamada se houver possibilidade de intervenção imediata; c) Fraseologia durante a recolha de dados: 'Solicito que fique calmo(a) e me repasse mais alguns dados para facilitar o atendimento'; d) Encerramento: 'Senhor(a), sua solicitação já está registrada. Assim que possível uma viatura será enviada ao local.'",
    "LOCAL DE ENCERRAMENTO": "Delegacia de Polícia Civil responsável."
  },
  {
    "GRUPO": "C",
    "NATUREZA": "C 04.000",
    "TÍTULO": "ENCONTRO DE CADÁVER",
    "DESCRIÇÃO": "Situação em que um corpo é encontrado fora das instalações hospitalares, como em via pública, residência, veículo, rio, lago ou terreno.",
    "AÇÕES": "a) Atender a ligação com a saudação padrão: 'Polícia Civil, 197. Qual é a sua solicitação?'; b) Solicitar nome completo e telefone do solicitante; c) Orientar o solicitante a acionar o SAMU pelo número 192 para constatação formal do óbito; d) Perguntar em qual local a vítima está e se há sinais de lesões, sangramento ou medicamentos próximos; e) O solicitante conhece a vítima? (S/N); f) Perguntar se a vítima possui histórico de criminalidade ou dependência química; g) Dividir a chamada com a Polícia Militar para manter o local guarnecido; h) Registrar a ocorrência no sistema.",
    "ATENDIMENTO LOCAL": "a) A equipe policial deverá isolar e preservar o local; b) Solicitar a presença da perícia e rabecão; c) Identificar testemunhas; d) Verificar possível identificação da vítima; e) Registrar o boletim de ocorrência.",
    "LOCAL DE ENCERRAMENTO": "Delegacia de Polícia Civil responsável."
  },
  {
    "GRUPO": "C",
    "NATUREZA": "C 05.000",
    "TÍTULO": "REMOÇÃO DE CADÁVER",
    "DESCRIÇÃO": "Situação em que o corpo se encontra em instalações hospitalares e necessita de autorização para remoção.",
    "AÇÕES": "a) Atender a ligação com a saudação padrão: 'Polícia Civil, 197. Qual é a sua solicitação?'; b) Solicitar um nome para contato e pelo menos dois telefones do hospital; c) Informar ao solicitante ou representante do hospital que deverá ligar novamente para o 197 para confirmação da autorização da remoção; d) Registrar todas as informações no sistema.",
    "ATENDIMENTO LOCAL": "a) Confirmar a autorização da remoção do cadáver; b) Verificar informações fornecidas pelo hospital; c) Encaminhar equipe responsável pela remoção; d) Registrar o atendimento.",
    "LOCAL DE ENCERRAMENTO": "Instituto Médico Legal ou órgão responsável pela remoção."
  }
];