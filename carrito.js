
// ======================================
// DOROT - CARRITO CON PHP Y MYSQL
// ======================================

// Enviar información a PHP
async function enviarDatos(url, datos = {}) {
    const respuesta = await fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams(datos)
    });

    const texto = await respuesta.text();

    let resultado;

    try {
        resultado = JSON.parse(texto);
    } catch {
        throw new Error("El servidor devolvió una respuesta inesperada.");
    }

    if (respuesta.status === 401) {
        alert(resultado.error || "Tenés que iniciar sesión.");
        window.location.href = "login.php";
        throw new Error("Sesión no iniciada.");
    }

    if (!respuesta.ok) {
        throw new Error(resultado.error || "Ocurrió un error.");
    }

    return resultado;
}


// ======================================
// ACTUALIZAR CARRITO
// ======================================

async function actualizarCarrito() {
    try {
        const respuesta = await fetch("carrito.php", {
            cache: "no-store"
        });

        if (respuesta.status === 401) {
            actualizarContador(0);
            return;
        }

        if (!respuesta.ok) {
            throw new Error("No se pudo cargar el carrito.");
        }

        const datos = await respuesta.json();

        const contenedor = document.getElementById("carrito-productos");
        const totalElemento = document.getElementById("carrito-total");

        if (!contenedor || !totalElemento) return;

        contenedor.innerHTML = "";

        let cantidadTotal = 0;

        if (!datos.productos || datos.productos.length === 0) {
            contenedor.innerHTML = "<p>Tu carrito está vacío.</p>";
        } else {
            datos.productos.forEach(producto => {
                cantidadTotal += Number(producto.cantidad);

                const elemento = document.createElement("div");
                elemento.className = "carrito-producto";

                const nombre = document.createElement("h3");
                nombre.textContent = producto.nombre;

                const precio = document.createElement("p");
                precio.textContent = "$" + Number(producto.precio).toLocaleString("es-AR");

                const subtotal = document.createElement("p");
                subtotal.textContent = "Subtotal: $" +
                    Number(producto.subtotal).toLocaleString("es-AR");

                const controles = document.createElement("div");
                controles.className = "carrito-controles";

                const menos = document.createElement("button");
                menos.textContent = "-";
                menos.onclick = () => cambiarCantidad(
                    producto.id,
                    Number(producto.cantidad) - 1
                );

                const cantidad = document.createElement("span");
                cantidad.textContent = producto.cantidad;

                const mas = document.createElement("button");
                mas.textContent = "+";
                mas.onclick = () => cambiarCantidad(
                    producto.id,
                    Number(producto.cantidad) + 1
                );

                const eliminar = document.createElement("button");
                eliminar.textContent = "Eliminar";
                eliminar.onclick = () => eliminarDelCarrito(producto.id);

                controles.append(menos, cantidad, mas, eliminar);
                elemento.append(nombre, precio, subtotal, controles);
                contenedor.appendChild(elemento);
            });
        }

        totalElemento.textContent = "$" +
            Number(datos.total || 0).toLocaleString("es-AR");

        actualizarContador(cantidadTotal);

    } catch (error) {
        console.error("Error al actualizar el carrito:", error);
    }
}


// ======================================
// CONTADOR DEL CARRITO
// ======================================

function actualizarContador(cantidad) {
    const contador = document.getElementById("contador-carrito");

    if (contador) {
        contador.textContent = cantidad;
    }
}


// ======================================
// AGREGAR PRODUCTO
// ======================================

async function agregarAlCarrito(productoId) {
    try {
        const resultado = await enviarDatos("agregar_carrito.php", {
            producto_id: productoId,
            cantidad: 1
        });

        await actualizarCarrito();
        abrirCarrito();

    } catch (error) {
        if (error.message !== "Sesión no iniciada.") {
            alert(error.message);
        }
    }
}


// ======================================
// CAMBIAR CANTIDAD
// ======================================

async function cambiarCantidad(productoId, cantidad) {
    if (cantidad <= 0) {
        await eliminarDelCarrito(productoId);
        return;
    }

    try {
        await enviarDatos("actualizar_carrito.php", {
            producto_id: productoId,
            cantidad: cantidad
        });

        await actualizarCarrito();

    } catch (error) {
        alert(error.message);
    }
}


// ======================================
// ELIMINAR PRODUCTO
// ======================================

async function eliminarDelCarrito(productoId) {
    try {
        await enviarDatos("eliminar_carrito.php", {
            producto_id: productoId
        });

        await actualizarCarrito();

    } catch (error) {
        alert(error.message);
    }
}


// ======================================
// VACIAR CARRITO
// ======================================

async function vaciarCarrito() {
    if (!confirm("¿Querés vaciar el carrito?")) return;

    try {
        await enviarDatos("vaciar_carrito.php");

        await actualizarCarrito();

    } catch (error) {
        alert(error.message);
    }
}


// ======================================
// ABRIR Y CERRAR CARRITO
// ======================================

function abrirCarrito() {
    document.getElementById("carrito-panel")?.classList.add("activo");
    document.getElementById("carrito-fondo")?.classList.add("activo");

    actualizarCarrito();
}

function cerrarCarrito() {
    document.getElementById("carrito-panel")?.classList.remove("activo");
    document.getElementById("carrito-fondo")?.classList.remove("activo");
}


// ======================================
// FINALIZAR COMPRA
// ======================================

async function finalizarCompra() {
    try {
        const confirmacion = confirm(
            "¿Estás seguro de que querés finalizar la compra?"
        );

        if (!confirmacion) return;

        const respuesta = await fetch("procesar_pedido.php", {
            method: "POST"
        });

        const resultado = await respuesta.text();

        if (!respuesta.ok) {
            alert(resultado);
            return;
        }

        if (resultado.includes("Compra registrada correctamente")) {
            alert(resultado);

            await actualizarCarrito();
            cerrarCarrito();

            window.location.href = "mis_pedidos.php";
        } else {
            alert(resultado);
        }

    } catch (error) {
        console.error("Error al finalizar la compra:", error);
        alert("No se pudo conectar con el servidor.");
    }
}


// ======================================
// CARGAR CARRITO AL ABRIR LA PÁGINA
// ======================================

document.addEventListener("DOMContentLoaded", function() {
    actualizarCarrito();
});
