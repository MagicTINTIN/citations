const plateInput = document.getElementById('plateinput');
const submitButtons = document.querySelectorAll('.subplate[type="submit"]');

function updtButtons() {
    const isValid = plateInput.checkValidity();
    submitButtons.forEach((button) => {
        button.disabled = !isValid;
    });
}

plateInput.addEventListener('input', e => updtButtons());
updtButtons();

document.addEventListener('DOMContentLoaded', function () {
    plateInput.focus();
    plateInput.select();
    plateInput.setSelectionRange(0, 0);
});