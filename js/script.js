document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('year').textContent = new Date().getFullYear();

    const menuIcon = document.getElementById('menu-icon');
    const headerMovil = document.querySelector('.header-movil');
    const headerPC = document.querySelector('.header-pc');
    const menu = document.querySelector('.header-movil nav');
    const dropdownBtnMovil = document.querySelector(".dropdown-movil");
    const dropdownMovil = document.querySelector(".dropdown-movil");
    const dropdownLogin = document.querySelector(".dropdown");
    const temporadaSelect = document.getElementById("temporadaSelect");

    menuIcon.addEventListener('click', function() {
        menu.classList.toggle('show');
        headerMovil.classList.toggle('open');
    });

    document.body.addEventListener('click', function (event) {
        if (!menu.contains(event.target) && event.target !== menuIcon) {
            menu.classList.remove('show');
            headerMovil.classList.remove('open');
        }
    });

    if (dropdownBtnMovil && dropdownMovil && headerMovil) {
        dropdownBtnMovil.addEventListener("click", function () {
            dropdownMovil.classList.toggle("active");

            if (dropdownMovil.classList.contains("active")) {
                headerMovil.classList.add("expanded");
            } else {
                headerMovil.classList.remove("expanded");
            }
        });
    }

    // Función para alternar la visibilidad del dropdown
    if (dropdownLogin) {
        dropdownLogin.addEventListener("click", function (event) {
            event.stopPropagation();
            dropdownLogin.classList.toggle("active");
        });
    
        // Cerrar el dropdown si se hace clic fuera de él
        document.addEventListener("click", function (event) {
            if (!dropdownLogin.contains(event.target)) {
                dropdownLogin.classList.remove("active");
            }
        });
    }
    
    if (window.getComputedStyle(headerPC).display !== 'none' && document.body.getAttribute('login') === 'true') {
        document.addEventListener('click', toggleDropdown);
    }

    if (temporadaSelect) {
        temporadaSelect.addEventListener("change", function () {
            const temporada = this.value;

            const formData = new FormData();
            formData.append("temporada", temporada);
            formData.append("ajax", "true");

            fetch(window.location.href, {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    location.reload();
                } else {
                    console.error("Error al actualizar la temporada:", data.message);
                }
            })
            .catch(error => console.error("Error:", error));
        });
    }

    if (window.location.pathname.includes('equipos')) {
        if (!equipoSeleccionado) {
            document.getElementById('equipos').style.display = 'flex';
            document.querySelector('.temporada-header').style.display = 'flex';
        } else {
            document.getElementById('equipos').style.display = 'none';
            document.querySelector('.temporada-header').style.display = 'none';
            
            const detallesEquipo = document.getElementById('detalle-' + equipoSeleccionado);
            if (detallesEquipo) {
                detallesEquipo.style.display = 'block';
            }
        }
    }
});
