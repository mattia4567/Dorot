console.log("DOROT: CARRITO NUEVO CARGADO");
// =====================================
// DOROT - SISTEMA DE CARRITO
// =====================================

// Recuperar productos guardados
let carrito = [];

try {
    carrito = JSON.parse(sessionStorage.getItem("dorotCarrito")) || [];
} catch (error) {
    carrito = [];
}

// Guardar carrito en sessionStorage
function guardarCarrito() {
    sessionStorage.setItem("dorotCarrito", JSON.stringify(carrito));
}

// Agregar productos
function agregarAlCarrito(nombre, precio, imagen = "") {

    let productoExistente = carrito.find(
        producto => producto.nombre === nombre
    );

    if (productoExistente) {
        productoExistente.cantidad++;
    } else {
        carrito.push({
            nombre: nombre,
            precio: precio,
            imagen: imagen,
            cantidad: 1
        });
    }

    guardarCarrito();
    actualizarCarrito();
    abrirCarrito();
}

// Eliminar productos
function eliminarDelCarrito(nombre) {

    carrito = carrito.filter(
        producto => producto.nombre !== nombre
    );

    guardarCarrito();
    actualizarCarrito();
}

// Modificar cantidad
function cambiarCantidad(nombre, cantidad) {

    let producto = carrito.find(
        producto => producto.nombre === nombre
    );

    if (!producto) return;

    producto.cantidad += cantidad;

    if (producto.cantidad <= 0) {
        eliminarDelCarrito(nombre);
    } else {
        guardarCarrito();
        actualizarCarrito();
    }
}

// Actualizar contenido del carrito
function actualizarCarrito() {

    let contenido = document.getElementById("carrito-productos");
    let contador = document.getElementById("contador-carrito");
    let total = document.getElementById("carrito-total");

    if (!contenido || !contador || !total) return;

    contenido.innerHTML = "";

    let cantidadTotal = 0;
    let precioTotal = 0;

    carrito.forEach(producto => {

        cantidadTotal += producto.cantidad;
        precioTotal += producto.precio * producto.cantidad;

        contenido.innerHTML += `
            <div class="carrito-producto">

                <div class="carrito-info">

                    <h3>${producto.nombre}</h3>

                    <p>$${producto.precio.toLocaleString("es-AR")}</p>

                    <div class="carrito-cantidad">

                        <button onclick="cambiarCantidad('${producto.nombre}', -1)">−</button>

                        <span>${producto.cantidad}</span>

                        <button onclick="cambiarCantidad('${producto.nombre}', 1)">+</button>

                    </div>

                    <button class="eliminar-producto"
                    onclick="eliminarDelCarrito('${producto.nombre}')">
                        Eliminar
                    </button>

                </div>

            </div>
        `;
    });

    if (carrito.length === 0) {
        contenido.innerHTML = `
            <div class="carrito-vacio">
                <p>Tu carrito está vacío.</p>
                <span>¡Descubrí nuestros productos!</span>
            </div>
        `;
    }

    contador.textContent = cantidadTotal;
    total.textContent = "$" + precioTotal.toLocaleString("es-AR");
}

// Abrir carrito
function abrirCarrito() {

    document.getElementById("carrito-panel").classList.add("activo");
    document.getElementById("carrito-fondo").classList.add("activo");

}

// Cerrar carrito
function cerrarCarrito() {

    document.getElementById("carrito-panel").classList.remove("activo");
    document.getElementById("carrito-fondo").classList.remove("activo");

}

// Vaciar carrito
function vaciarCarrito() {

    carrito = [];

    guardarCarrito();
    actualizarCarrito();

}

async function finalizarCompra() {

    if (carrito.length === 0) {
        alert("Tu carrito está vacío.");
        return;
    }

    try {

        const respuesta = await fetch("procesar_pedido.php", {
            method: "POST"
        });

        const resultado = await respuesta.text();

        if (!respuesta.ok) {
            alert(resultado);
            return;
        }

        carrito = [];
        guardarCarrito();
        actualizarCarrito();
        cerrarCarrito();

        alert(resultado);

        window.location.href = "mis_pedidos.php";

    } catch (error) {

        console.error(error);
        alert("No se pudo conectar con el servidor.");

    }
}