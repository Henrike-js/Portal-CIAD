// Utilitários compartilhados entre as páginas de atendimento e despacho

function updateClock() {
  const timeEl = document.querySelector(".clock-time");
  const dateEl = document.querySelector(".clock-date");
  if (!timeEl || !dateEl) return;

  const now = new Date();
  const pad = (n) => String(n).padStart(2, "0");

  timeEl.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

  const weekdays = [
    "Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira",
    "Quinta-Feira", "Sexta-Feira", "Sábado",
  ];
  const months = [
    "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
    "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro",
  ];

  dateEl.textContent = `${weekdays[now.getDay()]}, ${now.getDate()} de ${months[now.getMonth()]} de ${now.getFullYear()}`;
}

setInterval(updateClock, 1000);
updateClock();

function initFilters() {
  const pills = document.querySelectorAll(".pill[data-filter]");
  const cards = document.querySelectorAll(".card[data-category]");
  if (!pills.length || !cards.length) return;

  function applyFilter(filter) {
    cards.forEach((card) => {
      const category = card.dataset.category || "";
      card.style.display = (filter === "todos" || category === filter) ? "" : "none";
    });
  }

  pills.forEach((pill) => {
    pill.addEventListener("click", () => {
      pills.forEach((p) => p.classList.remove("pill-active"));
      pill.classList.add("pill-active");
      applyFilter(pill.dataset.filter);
    });
  });

  applyFilter("todos");
}

initFilters();
