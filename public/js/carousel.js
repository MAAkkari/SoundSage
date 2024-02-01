    window.addEventListener('DOMContentLoaded', (event) => {
            const buttons = document.querySelectorAll('.playlist_menu_btn');

            buttons.forEach(button => {
                const menuId = button.getAttribute('onclick').match(/menu-contextuel(\d+)/)[0];
                const menu = document.getElementById(menuId);

                if (menu) {
                    positionMenu(menu, button);
                }
            });
        });
        // reinitialise la position des boutons du menu contextuel lors du redimensionnement de la fenêtre
        window.addEventListener('resize', (event) => {
            const buttons = document.querySelectorAll('.playlist_menu_btn');

            buttons.forEach(button => {
                const menuId = button.getAttribute('onclick').match(/menu-contextuel(\d+)/)[0];
                const menu = document.getElementById(menuId);

                if (menu) {
                    positionMenu(menu, button);
                }
            });
        });
       

        let currentIndex = 0;

        // Déplace le carrousel dans la direction spécifiée
        function moveCarousel(direction) {
            const carousel = document.querySelector('.carousel-inner');
            const totalItems = 10;
            const visibleItems = 5;

            if (direction === 'prev') {
                currentIndex = (currentIndex === 0) ? totalItems - visibleItems : (currentIndex - 1 + totalItems) % totalItems;
            } else if (direction === 'next') {
                currentIndex = (currentIndex === totalItems - visibleItems) ? 0 : (currentIndex + 1) % totalItems;
            }

            const offset = -20 * currentIndex;
            carousel.style.transform = `translateX(${offset}%)`;
            // on doit attendre la fin de la transition pour réinitialiser la position des boutons sinon ils ne sont pas à la bonne place
            carousel.addEventListener('transitionend', resetButtonPositions);
        }
        
        // Réinitialise la position des boutons du menu contextuel après une transition
        function resetButtonPositions(){
            const buttons = document.querySelectorAll('.playlist_menu_btn');

            buttons.forEach(button => {
                const menuId = button.getAttribute('onclick').match(/menu-contextuel(\d+)/)[0];
                const menu = document.getElementById(menuId);

                if (menu) {
                    positionMenu(menu, button);
                    console.log("les bouttons ont été reset")
                }
            });
        }
        
        // appel de la fonction closeActiveMenus() lors du clic dans le vide (en dehors du menu contextuel) ou en dehors du bouton du menu contextuel
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.playlist_menu_btn') && !event.target.closest('.menu-contextuel')) {
                    closeActiveMenus();
                }
            });
        });

        // Positionne le menu à côté du bouton spécifié
        function positionMenu(menu, button) {
            const buttonRect = button.getBoundingClientRect();
            menu.style.top = `${buttonRect.top + buttonRect.height}px`;
            menu.style.left = `${buttonRect.left + buttonRect.width}px`;
        }

        // ferme tous les menus contextuels actifs
        function closeActiveMenus() {
            document.querySelectorAll('.menu-contextuel.menu-actif').forEach(function(menu) {
                menu.classList.remove('menu-actif');
            });
        }
