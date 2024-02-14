// Function to apply or remove the dark mode class on elements based on the current state
function applyDarkMode() {
    const isDarkMode = document.body.classList.contains('darkmode');
    const elements = document.querySelectorAll('body, footer, #textsvg, nav, header, header nav ul li, .form_session, .form_session input, table tbody tr, div li a, main table tbody tr td p, h3, h2, h1, a, .right_nav li a, .tab_wrapper, th, label, div, p, input, img, button');

    elements.forEach(element => {
        // Apply or remove the class based on the dark mode state, rather than toggling
        if (isDarkMode) {
            element.classList.add('darkmode');
        } else {
            element.classList.remove('darkmode');
        }
    });
}

// Function to initialize the dark mode based on the localStorage setting
function initializeDarkMode() {
    const body = document.querySelector('body');
    // Directly apply or remove the 'darkmode' class based on the localStorage setting
    if (localStorage.getItem('darkMode') === 'true') {
        body.classList.add('darkmode');
    } else {
        body.classList.remove('darkmode');
    }
    body.classList.remove('preload');
    applyDarkMode(); // Ensure all elements get the dark mode applied
    

}

// Setting up the event listener for the dark mode toggle button
document.addEventListener('DOMContentLoaded', function() {
    const dark_btns = document.querySelectorAll('.dark'); // Select all dark mode buttons
    if (dark_btns.length > 0) {
        dark_btns.forEach(function(btn) { // Iterate over each button
            btn.addEventListener('click', function() {
                document.body.classList.toggle('darkmode'); // Toggle dark mode for the body element
                applyDarkMode(); // Apply or remove dark mode from all relevant elements based on the new state

                // Update the localStorage setting based on the current state
                localStorage.setItem('darkMode', document.body.classList.contains('darkmode'));
            });
        });
    }
    initializeDarkMode(); // Apply dark mode on initial load if it was previously enabled
});

