function validateCard() {
    const cc = document.querySelector("input[name='cc_number']").value;
    if (!/^[0-9]{16}$/.test(cc)) {
        alert("Credit card must be 16 digits");
        return false;
    }
    return true;
}
