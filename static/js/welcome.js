const navbar = document.querySelector(".navbar");
const welcome = document.querySelector(".welcome");
const navbarToggle = document.querySelector("#navbarNav");

// Función para ajustar la altura de la imagen de fondo
const resizeBakgroundImg = () => {
  // Calcular la altura disponible restando la altura de la barra de navegación de la altura de la ventana
  const height = window.innerHeight - navbar.clientHeight;
  welcome.style.height = `${height}px`;
};

// Se llama a la funcion cuando 

// Transicion de la barra de navegacion termina
navbarToggle.ontransitionend = resizeBakgroundImg;

// Transicion de la barra de navegacion comienza
navbarToggle.ontransitionstart = resizeBakgroundImg;

// La ventana cambia de tamaño
window.onresize = resizeBakgroundImg;

// La pagina se recarga
window.onload = resizeBakgroundImg;