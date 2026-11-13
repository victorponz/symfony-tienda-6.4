//Immediately-Invoked Function Expression (IIFE)
(function(){
    // Este es el id de la ventana modal
    const infoProduct = $("#infoProduct");
    const cartModal = $("#cart-modal");
    const totalItems = $("#totalItems");
    

    // Evento click en todos los enlaces del producto con clase `open-info-product`
    $( "a.open-info-product" ).click(function(event) {      
        // Nos prevenimos de que el enlace no recargue la página
        event.preventDefault();

        // Hemos añadido el atributo `data-id` para poder saber que producto pedir 
        const id = $( this ).attr('data-id');
        // Esta es la ruta a la api
        const href = `/api/productos/${id}`;
        fetch(href)
          .then(response => {
            if (!response.ok) {
              throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
          })
          .then(data => {
            // Cuando devuelve los datos, `data` tiene los datos en json y ya puedo
            // sustituir la plantilla con los datos correctos
            $(infoProduct).find("#productName").text(data.name);
            $(infoProduct).find("#productPrice").text(data.price);
            $(infoProduct).find("#productImage").attr("src", "/img/" + data.photo);

            // Mostrar la ventana
            infoProduct.modal('show');
          })
          .catch(error => {
            console.error('Error al cargar el producto:', error);
          });
        });
    // La ventana tiene un botón con la clase `closeInfoProduct` que haremos que esconda la modal
    $(".closeInfoProduct").click(function (e) {
      infoProduct.modal('hide');
    });
    // Evento click en todos los enlaces del producto con clase `open-cart-product`
    $( "a.open-cart-product" ).click(function(event) {      
        cartModal.modal('show');
        // Nos prevenimos de que el enlace no recargue la página
        event.preventDefault();

        // Hemos añadido el atributo `data-id` para poder saber que producto pedir 
        const id = $( this ).attr('data-id');
        // Esta es la ruta a la api
        const href = `/cart/add`;
        fetch(href, {
          method: 'POST',
          body: new URLSearchParams({ id: id })
          })
          .then(response => {
            if (!response.ok) {
              throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
          })
          .then(data => {
            $( cartModal ).find( ".name" ).text(data.name);
            $( cartModal ).find( "#quantity" ).val(data.quantity);
            $( cartModal ).find( ".img-thumbnail" ).attr("src", "/img/" + data.photo);
           
            totalItems.text(data.totalItems);
           
            const updateButton = cartModal.find("#data-container .update")
            updateButton.unbind(); // Para evitar que se acumulen varios eventos click, eliminamos los anteriores
            updateButton.click(function(event){
                event.preventDefault();
                //Hacer un patch con la cantidad introducida por el usuario
                const cantidad = $( cartModal ).find( "#quantity" ).val();
                fetch('/cart/update', {
                  method: 'PATCH',
                  body: new URLSearchParams({ id: id , quantity: cantidad })
                  })
                  .then(response => {
                    if (!response.ok) {
                      throw new Error(`Error HTTP: ${response.status}`);
                    }
                    return response.json();
                  })
                  .then(data => {                  
                    totalItems.text(data.totalItems);
                  })
            });
            
          })
          .catch(error => {
            console.error('Error al cargar el producto:', error);
          });
    // La ventana tiene un botón con la clase `closeCart` que haremos que esconda la modal
    $(".closeCart").click(function (e) {
      cartModal.modal('hide');
    });
  })
    $( "a.remove-item" ).click(function(event) {
        event.preventDefault();
        const href = `/cart/delete`;
        const id = $( this ).attr('data-id');
        fetch(href, {
          method: 'DELETE',
          body: new URLSearchParams({ id: id })
          })
          .then(response => {
            if (!response.ok) {
              throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
          })
          .then(data => {           
            totalItems.text(data.totalItems);        
            $( "#totalCart" ).text(new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(data.total));  
            //Ahora eliminanos el contenedor del producto
            $(`#item-${id}`).hide('slow', function(){ $(`#item-${id}`).remove(); });
          })
      });
})();