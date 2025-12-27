/* Basic client-side validation */

document.addEventListener("DOMContentLoaded", () => {

    const forms = document.querySelectorAll("form");

    forms.forEach(form => {
        form.addEventListener("submit", event => {
            const inputs = form.querySelectorAll("input[required]");

            for (let input of inputs) {
                if (input.value.trim() === "") {
                    alert("Please fill all required fields");
                    input.focus();
                    event.preventDefault();
                    return;
                }
            }
        });
    });

});

/* Confirmation for dangerous actions */
function confirmAction(message = "Are you sure?") {
    return confirm(message);
}
