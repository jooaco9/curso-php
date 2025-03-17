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

  contactCol.innerHTML = `
    <div class="card mx-auto shadow-sm" style="max-width: 320px;">
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

// Ejecutar cuando el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
  // Manejo de búsqueda con debounce para no hacer solicitudes con cada pulsación
  let searchTimeout = null;
  const searchInput = document.getElementById('search-input');

  if (searchInput) {
    searchInput.addEventListener('input', function() {
      clearTimeout(searchTimeout);
      
      searchTimeout = setTimeout(() => {
        const searchTerm = searchInput.value.trim();
        
        // Realizar la solicitud AJAX
        fetch(`search.php?term=${encodeURIComponent(searchTerm)}`)
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