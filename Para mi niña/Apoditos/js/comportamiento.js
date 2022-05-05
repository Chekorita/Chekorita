const words = [
    "AMOR",
    "DULCESITA",
    "LILIANITA",
    "BEBE",
    "PRINCESA",
    "MI VIDA",
    "CORAZON",
    "MI PRECIOSA",
    "MI NIÑA",
    "MI BEBE",
    "TE AMO",
    "TE QUIERO",
    "ENAMORADO DE TI",
    "ERES ARTE",
    "MI CIELO",
    "MI REINA",
    "PRINCESA",
    "BONITA",
    "AMOR",
    "CARIÑO",
    "MI CIELO",
    "MI VIDA",
    "CORAZÓN",
    "CONEJITA",
    "MI DIOSA",
    "BONITA",
    "BELLA",
    "JOYA DE PERSONA",
    "PEQUEÑA",
    "TE AMO",
    "TE QUIERO",
    "NIÑA",
    "BEBESITA",
    "BABY",
    "LOVE",
    "MY LOVE",
    "I LOVE",
    "CARIÑO",
    "GUAPA",
    "PRECIOSA",
    "LINDA",
    "BELLA",
    "TE AMO",
    "MI AMOR",
    "MI BEBE",
    "MI VIDA",
    "MI LUZ",
    "MI INSPIRACION",
    "OJITOS",
    "TIERNA",
    "MI TODO",
    "MI MUNDO",
    "TE ADORO",
    "ÁNGEL",
    "TE AMO",
    "CORAZON",
    "SEÑORITA",
    "TERNURITA",
    "MI EEVEE",
    "PRECIOSA",
    "COSITA",
    "NIÑA",
    "TE AMO",
    "MI ESPOSA",
    "MI NOVIA"
];

const dom = {
    love: document.querySelector(".love")
};

dom.love.style.setProperty("--particles", words.length);

words.forEach((word, i) => {
    let span = document.createElement("span");
    span.style.setProperty("--n", i + 1);
    span.innerText = word;
    dom.love.appendChild(span);
});