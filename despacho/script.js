// Bloqueia clique em cards sem href configurado
document.querySelectorAll(".card").forEach((card) => {
  card.addEventListener("click", (event) => {
    const href = card.getAttribute("href");
    if (!href || href.trim() === "") {
      event.preventDefault();
      alert("Este sistema ainda não possui link configurado.");
    }
  });
});
