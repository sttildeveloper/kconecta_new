function BulmaPagination (id_table, row_visible = null, id_container_table = null){
    // debes pasar un id de una tabla, este tabla debe contener el thead y tbody
    // el row visible es el numero de filas que quieres que se visualice en la tabla
    // para que sea automático el numero de filas a mostrar, debes pasar como parametro null y pasar un id del contenedor que quieres que se llene
    // debes asegurarte que el alto de las filas sean iguales para todos 

    let n_row = row_visible;
    let n_index = 1;
    const pagination_previous = document.querySelector(".pagination-previous");
    const pagination_next = document.querySelector(".pagination-next");
    const pagination_list = document.querySelector(".pagination-list");
    const table = document.getElementById(id_table);
    const tbody = table.querySelector("tbody");
    const tr = tbody.querySelectorAll("tr");
    if (!n_row){
        const ctn_table = document.getElementById(id_container_table);
        let heightAvailability = ctn_table.clientHeight;
        let heightThead = table.querySelector("thead").clientHeight;
        let heightTr = tr[0].clientHeight;
        n_row = Math.floor((heightAvailability - heightThead) / heightTr);
    }
    const update_front = () =>{
        tr.forEach((ta, i) =>{
            if (((n_row * n_index) - n_row) <= i && i < (n_row * n_index)){
                tr[i].removeAttribute("style");
            }else{
                ta.style.display = "none";
            };
        });
    }
    update_front();
    let quantity_links = Math.ceil(tr.length / n_row);

    pagination_list.innerHTML = "";
    for (let i = 0; i < quantity_links; i++){
        let bulma_pagination_li = "";
        if (i+1 === 1){
            bulma_pagination_li = "<li><a href='#' class='pagination-link is-current' aria-label='Goto page "+ String(i+1) +"'>"+ String(i+1) +"</a></li>";
        }else{
            bulma_pagination_li = "<li><a href='#' class='pagination-link' aria-label='Goto page "+ String(i+1) +"'>"+ String(i+1) +"</a></li>";
        }
        pagination_list.insertAdjacentHTML("beforeend", bulma_pagination_li)
    };
    
    const pagination_links = document.querySelectorAll(".pagination-link");
    pagination_links.forEach(p =>{
        p.addEventListener("click", ()=>{
            pagination_links.forEach(pl =>{
                pl.classList.remove("is-current")
            });
            p.classList.add("is-current");
            n_index = parseInt(p.textContent);
            update_front();
            if (n_index > 1){
                pagination_previous.classList.remove("is-disabled");
            }else{
                pagination_previous.classList.add("is-disabled");
            };
            if (n_index === quantity_links){
                pagination_next.classList.add("is-disabled");
            }else{
                pagination_next.classList.remove("is-disabled");
            };
        });
    });
    pagination_next.addEventListener("click", ()=>{
        if (n_index < quantity_links){
            n_index += 1;
            update_front();
            pagination_next.classList.remove("is-disabled");
            if (n_index === quantity_links){
                pagination_next.classList.add("is-disabled");    
            }
        }else{
            pagination_next.classList.add("is-disabled");
        };
        pagination_previous.classList.remove("is-disabled");
        pagination_links.forEach(pl =>{
            if (parseInt(pl.textContent) === n_index){
                pl.classList.add("is-current");
            }else{
                pl.classList.remove("is-current")
            }
        });
    });
    pagination_previous.addEventListener("click", ()=>{
        if (n_index > 1){
            n_index -= 1;
            update_front();
            pagination_previous.classList.remove("is-disabled");
            if (n_index === 1){
                pagination_previous.classList.add("is-disabled");
            };
        }else{
            pagination_previous.classList.add("is-disabled");
        };
        pagination_next.classList.remove("is-disabled");
        pagination_links.forEach(pl =>{
            if (parseInt(pl.textContent) === n_index){
                pl.classList.add("is-current");
            }else{
                pl.classList.remove("is-current")
            }
        });
    });   
}
function BulmaTableSearch(id_tbody, id_input, mode="all", select=false){
    // mode => all : el valor del input está en alguna parte de un texto
    // mode => start : el valor del input está al inicio de un texto
    const table_tbody = document.getElementById(id_tbody, id_input);
    const input_search = document.getElementById(id_input);
    input_search.addEventListener("input", ()=>{
        const table_tr = table_tbody.querySelectorAll("tr");
        table_tr.forEach(tr=>{
            let state_temp_tr = false;
            const table_tr_td = tr.querySelectorAll("td");
            table_tr_td.forEach(td=>{
                if (mode === "all"){
                    if (td.textContent.toLowerCase().includes(input_search.value.toLowerCase())){
                        if (!state_temp_tr){
                            state_temp_tr = true;
                        }
                    }
                }else if (mode === "start"){
                    if (td.textContent.toLowerCase().startsWith(input_search.value.toLowerCase())){
                        if (!state_temp_tr){
                            state_temp_tr = true;       
                        }
                    }
                }
            });
            if (state_temp_tr){
                tr.removeAttribute("style");
            }else{
                tr.style.display = "none";
            }
        });
    })   
}