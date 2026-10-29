document.addEventListener("DOMContentLoaded", async()=>{
    let row_app = null;
    const app_ref_main = document.getElementById("app-ref-main");
    const form_data = new FormData();
    form_data.append("post_id", app_ref_main.value);
    await fetch("/api/visitor/save", {
        method: "POST",
        body : form_data,
    }).then(res => res.json()).then(data =>{row_app = data.id})
    
    const btns_u = document.querySelectorAll(".btn-contact-redirect");
    btns_u.forEach((btn)=>{
        const form_data = new FormData();
        form_data.append("row_id", row_app);
        btn.addEventListener("click", async() =>{
            await fetch("/api/visitor/contacted", {
                method : "POST",
                body: form_data,
            }).then(res => res.json()).then(data =>{})
        })
    })
})

document.addEventListener("click", async(e)=>{
    const tag_psu = e.target.className.includes("A7x9Vb2QmL-psu");
    if(!tag_psu) return;
    const form_data = new FormData();
    const post_id = e.target.getAttribute("data-i");
    const post_col = e.target.getAttribute("data-col");
    form_data.append("_i", post_id);
    form_data.append(post_col, true);
    await fetch("/api/property_stats/register", {
        method: "POST",
        body: form_data,
    }).then(res => res.json()).then(data => {});
})