// carrito.js
let carrito = JSON.parse(sessionStorage.getItem("carrito")) || [];

document.querySelectorAll(".producto button").forEach((btn) => {
    btn.addEventListener("click", () => {
        let producto = btn.parentElement.querySelector("h3").textContent;
        let precio = parseFloat(btn.parentElement.querySelector("p").textContent.replace("€", ""));
        
        let itemIndex = carrito.findIndex(item => item.producto === producto);
        if (itemIndex !== -1) {
            carrito[itemIndex].cantidad += 1;
        } else {
            carrito.push({ producto, precio, cantidad: 1 });
        }
        
        sessionStorage.setItem("carrito", JSON.stringify(carrito));
        actualizarCarrito();
    });
});

function actualizarCarrito() {
    let listaCarrito = document.getElementById("lista-carrito");
    if (!listaCarrito) return;
    listaCarrito.innerHTML = "";

    carrito.forEach((item) => {
        let li = document.createElement("li");
        li.textContent = `${item.producto} - ${item.cantidad} x ${item.precio}€`;
        listaCarrito.appendChild(li);
    });

    if (carrito.length === 0) {
        listaCarrito.innerHTML = "<li>No hay productos en el carrito.</li>";
    }
}

document.getElementById("vaciar-carrito")?.addEventListener("click", () => {
    carrito = [];
    sessionStorage.removeItem("carrito");
    actualizarCarrito();
});

// Código para la página de pago
if (window.location.pathname.includes("pago.html")) {
    document.addEventListener("DOMContentLoaded", function () {
        let resumenPedido = document.getElementById("resumen-pedido");
        let totalElemento = document.getElementById("total");
        
        if (carrito.length === 0) {
            resumenPedido.innerHTML = "<p>No hay productos en el carrito</p>";
            totalElemento.textContent = "Total: 0.00 €";
            return;
        }

        let total = 0;
        let contenidoHTML = "<ul>";
        
        carrito.forEach(producto => {
            contenidoHTML += `<li>${producto.producto} - ${producto.cantidad} x ${producto.precio}€</li>`;
            total += producto.cantidad * producto.precio;
        });

        contenidoHTML += "</ul>";
        resumenPedido.innerHTML = contenidoHTML;
        totalElemento.textContent = `Total: ${total.toFixed(2)} €`;
    });
}
