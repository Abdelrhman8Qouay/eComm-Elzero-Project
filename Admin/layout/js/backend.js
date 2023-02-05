
// Manage Members Pages ----------------------------->

// For All Inputs And When Invalid Cases
inputs = document.querySelectorAll(".form-control");

inputs.forEach(input => {
    input.addEventListener("invalid", ()=> {
        if(input.parentElement.children.length >= 3) {
            false;
        } else {
            let ele = document.createElement("span")
            ele.classList.add("asterisk");
            ele.innerText = "*";
            input.after(ele);
        }
    })
});


// Confirmation For Delete Buttons If You Want To Continue
btns_confirm_delete = document.querySelectorAll(".confirma-message");
modal_confirm = document.getElementById("confirm-delete");

btns_confirm_delete.forEach(btn_confirm_delete=>{
    // Get The Buttons Of (yes / no)
    yes_btn = document.getElementById("yes-btn");
    close_btn = document.getElementById("close-btn");

    btn_confirm_delete.addEventListener("click", ()=> {
        old_href = btn_confirm_delete.href;
        btn_confirm_delete.href="javascript:void(0)";
        modal_confirm.classList.add("show");

        yes_btn.addEventListener("click", ()=> {
            btn_confirm_delete.href = old_href;
            modal_confirm.classList.remove("show");
            window.location = old_href;
        });

        close_btn.addEventListener("click", ()=> {
            btn_confirm_delete.href = old_href;
            modal_confirm.classList.remove("show");
        });
    })
})