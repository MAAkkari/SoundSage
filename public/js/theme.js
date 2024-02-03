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
    const spans = document.querySelectorAll('h1 span');
    const navIcons = document.querySelectorAll('.icon');
    const logos = document.querySelectorAll('.logo path');

    spans.forEach(span => {
        span.style.color = color; // Change the text color of spans
    });
    navIcons.forEach(navIcon => {
        navIcon.style.color = color; // Change the text color of navIcons
    });
    logos.forEach(logo => {
        logo.style.fill = color; // Change the fill color of logos
    });
}

// Function to reset colors to original
function resetThemeColor() {
    // Select the same elements that were modified in applyThemeColor
    const elementsToReset = document.querySelectorAll('h1 span, .icon, .logo path');

    elementsToReset.forEach(element => {
        element.style.color = ''; // Remove inline color style for spans and icons
        element.style.fill = '';  // Remove inline fill style for SVG paths
    });

    // Additional reset actions as needed
}
