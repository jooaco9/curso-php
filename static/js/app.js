function createContactCard(contact) {
  // Crear el contenedor principal
  const contactCol = document.createElement('div');
  contactCol.className = 'col-md-5 col-lg-4 mb-4';

  // Crear la estructura de la tarjeta
  let addressesHtml = '';

  if (contact.addresses && contact.addresses.length > 0) {
    addressesHtml = contact.addresses.map(addr => 
      `<li><a class="dropdown-item">${addr.adress}</a></li>`
    ).join('');
  } else {
    addressesHtml = '<li><a class="dropdown-item text-muted">No addresses available</a></li>';
  }

  const favoriteClass = contact.favorite == 1 ? 'bi-star-fill' : 'bi-star';

  contactCol.innerHTML = `
    <div class="card mx-auto shadow-sm position-relative" style="max-width: 320px;">
      <div class="position-absolute" style="top: 16px; right: 22px;">
        <button id="fav-btn-${contact.id}" class="btn btn-sm text-warning border-0 p-0 favorite-btn" data-contact-id="${contact.id}" onclick=favorite(event)>
          <i class="bi ${favoriteClass} fs-5"></i> 
        </button>
      </div>
      <div class="card-body p-3">
        <h3 class="card-title text-capitalize fs-4">${contact.name}</h3>
        <p class="text-muted mb-2">${contact.phone_number}</p>
        
        <div class="dropdown mb-2">
          <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
            id="addressDropdown${contact.id}" data-bs-toggle="dropdown" aria-expanded="false">
            Addresses
          </button>
          <ul class="dropdown-menu" aria-labelledby="addressDropdown${contact.id}">
            ${addressesHtml}
          </ul>
        </div>
        
        <div class="d-flex justify-content-between mb-2">
          <a href="edit.php?id=${contact.id}" class="btn btn-sm btn-outline-primary">Edit Contact</a>
          <a href="delete.php?id=${contact.id}" class="btn btn-sm btn-outline-danger">Delete Contact</a>
        </div>
        <div class="d-flex justify-content-between">
          <a href="editAdresses.php?id=${contact.id}" class="btn btn-sm btn-outline-primary">Edit Adresses</a>
          <a href="deleteAdresses.php?id=${contact.id}" class="btn btn-sm btn-outline-danger">Delete Adress</a>
        </div>
      </div>
    </div>
  `;
  
  return contactCol;
}

// Función para actualizar la lista de contactos
function updateContactsList(contacts) {
  const contactsContainer = document.getElementById('contacts-container');
  contactsContainer.innerHTML = '';

  if (contacts.length === 0) {
    const noContactsDiv = document.createElement('div');
    noContactsDiv.className = 'col-md-4 mx-auto';
    noContactsDiv.innerHTML = `
      <div class="card card-body text-center">
        <p>No contacts found</p>
        <a href="add.php" class="btn btn-primary">Add One!</a>
      </div>
    `;
    contactsContainer.appendChild(noContactsDiv);
    return;
  }

  contacts.forEach(contact => {
    contactsContainer.appendChild(createContactCard(contact));
  });
}

function favorite(event) {
  event.preventDefault();
  event.stopPropagation();

  // Obtengo el boton que se clickeo
  const button = event.currentTarget;
  const contactId = button.getAttribute('data-contact-id');
  const icon = button.querySelector('i.bi');

  // Nuevo estado del icono
  const isFavorite = icon.classList.contains('bi-star-fill');
  const newFavoriteStatus = isFavorite ? 0 : 1;
  console.log("stauts", newFavoriteStatus);
  console.log('id', contactId);

  const formData = new FormData();
  formData.append('contact_id', contactId);
  formData.append('favorite', newFavoriteStatus);

  // Solicitud AJAX para actualizar estado en la DB
  fetch('../api/favorite.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {

      // Actualizo icono segun el estado
      if (newFavoriteStatus === 1) {
        icon.classList.remove('bi-star');
        icon.classList.add('bi-star-fill');
      } else {
        icon.classList.remove('bi-star-fill');
        icon.classList.add('bi-star');
      }

    } else {
      console.error('Error al actualizar favorito:', data.message);
    }
  })
  .catch(error => {
    console.error("Error en la solicitud:", error);
  });
}

// Ejecutar cuando el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
  // Manejo de búsqueda con debounce para no hacer solicitudes con cada pulsación
  let searchTimeout = null;
  const searchInput = document.getElementById('search-input');
  
  // Para saber si estoy en la pagina de favorites
  let isFavoritesPage = window.isFavoritesPage || false;

  if (searchInput) {
    searchInput.addEventListener('input', function() {
      clearTimeout(searchTimeout);
      
      searchTimeout = setTimeout(() => {
        const searchTerm = searchInput.value.trim();

        let searchURL = `../api/search.php?term=${encodeURIComponent(searchTerm)}`;

        if (isFavoritesPage) {
          searchURL += '&favorite=1';
        }
        
        // Realizar la solicitud AJAX
        fetch(searchURL)
          .then(response => response.json())
          .then(contacts => {
            updateContactsList(contacts);
          })
          .catch(error => {
            console.error('Error en la búsqueda:', error);
          });
      }, 300); // Esperar 300ms después de que el usuario deje de escribir
    });

    // Evitar que el formulario de búsqueda se envíe al presionar Enter
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
      searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
      });
    }
  }
});