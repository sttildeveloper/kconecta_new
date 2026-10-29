const return_page = document.querySelectorAll(".btn-return");
const ctn_1 = document.querySelector(".container-1");
const ctn_2 = document.querySelector(".container-2");
const ctn_3 = document.querySelector(".container-3");
const btn_redirect_property = document.getElementById("redirect-property");
const btn_redirect_service = document.getElementById("redirect-service");
btn_redirect_property?.addEventListener("click", ()=>{
    btn_redirect_property.style.backgroundColor = "var(--color-main-1)";
    btn_redirect_property.style.color = "white";
    btn_redirect_service.removeAttribute("style");

    // ctn_1.classList.remove("container-search-open-box");
    // ctn_1.classList.add("container-search-close-box");

    ctn_2.classList.remove("container-search-close-box");
    ctn_2.classList.add("container-search-open-box");
    
    ctn_3.classList.remove("container-search-open-box");
    ctn_3.classList.add("container-search-close-box");
});
btn_redirect_service?.addEventListener("click", ()=>{
    btn_redirect_service.style.backgroundColor = "var(--color-main-1)";
    btn_redirect_service.style.color = "white";
    btn_redirect_property.removeAttribute("style");

    // ctn_1.classList.remove("container-search-open-box");
    // ctn_1.classList.add("container-search-close-box");
    
    ctn_2.classList.remove("container-search-open-box");
    ctn_2.classList.add("container-search-close-box");
    
    ctn_3.classList.remove("container-search-close-box");
    ctn_3.classList.add("container-search-open-box");
});

return_page?.forEach(el =>{
    el.addEventListener("click", ()=>{
        // ctn_1.classList.remove("container-search-close-box");
        // ctn_1.classList.add("container-search-open-box");
        
        ctn_2.classList.remove("container-search-open-box");
        ctn_2.classList.add("container-search-close-box");
        
        ctn_3.classList.remove("container-search-open-box");
        ctn_3.classList.add("container-search-close-box");
    })
})
 