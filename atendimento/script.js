function updateClock() {
  const timeEl = document.querySelector(".clock-time");
  const dateEl = document.querySelector(".clock-date");

  const now = new Date();

  const horas   = String(now.getHours()).padStart(2, "0");
  const minutos = String(now.getMinutes()).padStart(2, "0");
  const segundos = String(now.getSeconds()).padStart(2, "0");

  timeEl.textContent = `${horas}:${minutos}:${segundos}`;

  const dias = ["Domingo","Segunda-feira","Terça-feira","Quarta-feira","Quinta-feira","Sexta-feira","Sábado"];
  const meses = [
    "Janeiro","Fevereiro","Março","Abril","Maio","Junho",
    "Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"
  ];

  const diaSemana = dias[now.getDay()];
  const dia       = now.getDate();
  const mes       = meses[now.getMonth()];

  dateEl.textContent = `${diaSemana}, ${dia} de ${mes}`;
}

// Atualiza ao carregar a página
updateClock();

// Atualiza a cada segundo
setInterval(updateClock, 1000);

// ---------------- FILTRO DE CARDS ----------------
function initFilters() {
  const pills = document.querySelectorAll(".pill[data-filter]");
  const cards = document.querySelectorAll(".card[data-category]");

  if (!pills.length || !cards.length) return;

  pills.forEach((pill) => {
    pill.addEventListener("click", () => {
      const filtro = pill.dataset.filter;

      // marca visualmente o filtro ativo
      pills.forEach((p) => p.classList.remove("pill-active"));
      pill.classList.add("pill-active");

      // mostra/esconde cards
      cards.forEach((card) => {
        const categoria = card.dataset.category || "";

        if (filtro === "todos" || categoria === filtro) {
          card.style.display = "";
        } else {
          card.style.display = "none";
        }
      });
    });
  });
}

initFilters();