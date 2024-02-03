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

    spans.forEach(span => span.style.color = color);
    navIcons.forEach(navIcon => navIcon.style.color = color);
    logos.forEach(logo => logo.style.fill = color);
    trigger.style.backgroundColor = color;

    // Handling ::before pseudo-element gradient (existing code)
    let dynamicStyles = document.getElementById('dynamic-styles');
    if (!dynamicStyles) {
        dynamicStyles = document.createElement('style');
        dynamicStyles.id = 'dynamic-styles';
        document.head.appendChild(dynamicStyles);
    }

    // Update or insert new styles for both ::before gradient and ::after background-color
    dynamicStyles.innerHTML = `
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
    // Reset inline styles for spans, navIcons, and logos
    const elementsToReset = document.querySelectorAll('h1 span, .icon, .logo path , .color-picker-trigger');
    elementsToReset.forEach(element => {
        element.style.color = '';
        element.style.fill = '';
        element.style.backgroundColor = '';
    });

    // Reset styles for ::before and ::after pseudo-elements
    const dynamicStyles = document.getElementById('dynamic-styles');
    if (dynamicStyles) {
        dynamicStyles.innerHTML = `
            .navbar-nav li.nav_element::before {
                background: linear-gradient(90deg, rgba(48, 122, 208, 0.33) 0%, rgba(48, 122, 208, 0.00) 100%);
            }
            .navbar-nav li.nav_element::after {
                background-color: #007BFF;
            }
        `;
    }
}
