document.addEventListener('DOMContentLoaded', function() {
    const colorPicker = document.getElementById('themeColorPicker');
    const resetButton = document.getElementById('resetThemeColor'); // Get the reset button

    // Load the previously selected theme color if it exists
    const savedColor = localStorage.getItem('themeColor');
    if (savedColor) {
        applyThemeColor(savedColor);
        colorPicker.value = savedColor; // Set the color picker's value to the saved color
    }

    // Listen for color changes
    colorPicker.addEventListener('input', function() {
        const color = this.value;
        applyThemeColor(color);
        localStorage.setItem('themeColor', color); // Save the selected color to localStorage
    });

    // Listen for reset action
    resetButton.addEventListener('click', function() {
        resetThemeColor();
        localStorage.removeItem('themeColor'); // Remove the saved color from localStorage
        colorPicker.value = '#ffffff'; // Reset color picker to default or original color if you have one
    });
});

function applyThemeColor(color) {
    // Existing code for spans, navIcons, and logos
    const spans = document.querySelectorAll('h1 span');
    const navIcons = document.querySelectorAll('.icon');
    const logos = document.querySelectorAll('.logo path');
    const trigger = document.querySelector('.color-picker-trigger');
    const playPauseBtn = document.querySelector('#playPauseBtn'); // Correctly select the play/pause button
    const progress = document.querySelector('#progress');
    const volumeSlider = document.querySelector('#volumeSlider'); // Correctly select the volume slider
    const carousel_btns = document.querySelectorAll('.carousel-btns');
    const i_theme = document.querySelectorAll('.i-theme');
    const theme_color = document.querySelectorAll('.theme-color .fa-heart');

    spans.forEach(span => span.style.color = color);
    navIcons.forEach(navIcon => navIcon.style.color = color);
    logos.forEach(logo => logo.style.fill = color);
    trigger.style.backgroundColor = color;
    playPauseBtn.style.backgroundColor = color; // Apply the color to the play/pause button
    progress.style.backgroundColor = color; // Apply the color to the progress bar
    volumeSlider.style.backgroundColor = color; // Apply the color to the volume slider
    carousel_btns.forEach(carousel_btn => carousel_btn.style.backgroundColor = color);
    i_theme.forEach(i => i.style.color = color);
    theme_color.forEach(i => i.style.color = color);

    // Handling ::before pseudo-element gradient (existing code)
    let dynamicStyles = document.getElementById('dynamic-styles');
    if (!dynamicStyles) {
        dynamicStyles = document.createElement('style');
        dynamicStyles.id = 'dynamic-styles';
        document.head.appendChild(dynamicStyles);
    }

    // couleur de la barre de volume , du gradient de la nav bar , et du ::after a coté de chaque li 
    dynamicStyles.innerHTML = `
    
        input[type='range']::-webkit-slider-thumb {
            width: 10px;
            -webkit-appearance: none;
            height: 10px;
            cursor: pointer;
            background: ${color}; /* Updated thumb background color */
            box-shadow: -80px 0 0 80px ${color}; /* Updated filled track color */
        }
        
        .navbar-nav li.nav_element::before {
            background: linear-gradient(90deg, ${color}33 0%, ${color}00 100%);
        }
        .navbar-nav li.nav_element::after {
            background-color: ${color};
        }
    `;
}


// Function to reset colors to original
function resetThemeColor() {
    // Reset inline styles for spans, navIcons, logos, etc.
    const elementsToReset = document.querySelectorAll(' theme-color, .i-theme, h1 span, .icon, .logo path, .color-picker-trigger, #playPauseBtn, #progress, #volumeSlider, .carousel-btns ');
    elementsToReset.forEach(element => {
        element.style.color = '';
        element.style.fill = '';
        element.style.backgroundColor = '';
    });

    // Reset styles for ::before, ::after pseudo-elements, and slider styles
    const dynamicStyles = document.getElementById('dynamic-styles');
    if (dynamicStyles) {
        dynamicStyles.innerHTML = `
            .navbar-nav li.nav_element::before {
                background: linear-gradient(90deg, rgba(48, 122, 208, 0.33) 0%, rgba(48, 122, 208, 0.00) 100%);
            }
            .navbar-nav li.nav_element::after {
                background-color: #007BFF;
            }
            input[type='range']::-webkit-slider-thumb {
                width: 10px;
                -webkit-appearance: none;
                height: 10px;
                cursor: pointer;
                background: #307AD0; /* Original thumb color */
                box-shadow: -80px 0 0 80px #307AD0; /* Original filled track color */
            }
            /* Add default styles for other browsers if you've styled them */
        `;
    }
}