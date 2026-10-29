const search_property_function = () =>{

    const template_result = (address, locality, quantity, category, type) =>{
        let t = '<a href="/result?ps=1&ca='+category+'&ty='+type+'&address='+address+'" class="link-container">'+
                '    <div class="ctn-address">'+
                '        <h3>'+ address +'</h3>'+
                '    </div>'+
                '    <div class="ctn-others-details">'+
                '        <span class="ctn-location-main">'+
                '            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="" d="M12 2c-4.4 0-8 3.6-8 8c0 5.4 7 11.5 7.3 11.8c.2.1.5.2.7.2s.5-.1.7-.2C13 21.5 20 15.4 20 10c0-4.4-3.6-8-8-8m0 17.7c-2.1-2-6-6.3-6-9.7c0-3.3 2.7-6 6-6s6 2.7 6 6s-3.9 7.7-6 9.7M12 6c-2.2 0-4 1.8-4 4s1.8 4 4 4s4-1.8 4-4s-1.8-4-4-4m0 6c-1.1 0-2-.9-2-2s.9-2 2-2s2 .9 2 2s-.9 2-2 2"/></svg>'+
                '            <span>'+locality+'</span>'+
                '        </span>'+
                '        <span class="ctn-quantity-result">'+ quantity +'</span>'+
                '    </div>'+
                '</a>';
        return t;
    }
    const ctn_properties = document.getElementById("container-main-results-properties");
    const input_search_properties = document.getElementById("input-search-address-property");
    const category_property = document.getElementById("category_property_id");
    const type_property = document.getElementById("type_property_id");
    
    const route_temp = "/api/properties?";
    const search_property = async(route)=>{
        ctn_properties.innerHTML = '<div class="container-loader-search-property"><div class="loader-ui-verse-simple"></div><span>Buscando...</span></div>';
        await fetch(route).then(res => res.json()).then(data =>{
            ctn_properties.innerHTML = "";
            
            if (data.status === 200){
                if (Object.keys(data.province).length >= 0){
                    Object.entries(data.province).forEach(([clave, valor]) =>{
                        const response = template_result(clave, "Provincia", valor, category_property.value, type_property.value);
                        ctn_properties.insertAdjacentHTML("beforeend", response);    
                    })
                }
                if (Object.keys(data.province).length >= 0){
                    Object.entries(data.data).forEach(([clave, valor]) =>{
                        const response = template_result(clave, "Municipio", valor, category_property.value, type_property.value);
                        ctn_properties.insertAdjacentHTML("beforeend", response);
                    })
                }
                if (data.data.length == 0 && Object.keys(data.province).length == 0){
                    ctn_properties.innerHTML= '<div class="search-not-found">'+
                                                '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><g fill="none" stroke="#b8aaaa" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="#666666"><path d="M17.5 17.5L22 22m-2-11a9 9 0 1 0-18 0a9 9 0 0 0 18 0"/><path d="M9.492 7.5c-.716.043-1.172.163-1.5.491c-.33.329-.449.785-.492 1.501M12.508 7.5c.716.043 1.172.163 1.5.491c.33.329.449.785.492 1.501m-.008 3.13c-.049.651-.173 1.076-.483 1.387c-.329.328-.785.448-1.501.491m-3.016 0c-.716-.043-1.172-.163-1.5-.491c-.311-.311-.435-.736-.484-1.388"/></g></svg>'+
                                                '<span>No hay resultados</span>'+
                                            '</div>';
                }
            }
        });
    }
    
    input_search_properties.addEventListener("input", async()=>{
        if (input_search_properties.value.length > 0 && input_search_properties.value.trim()){
            search_property(`/api/properties?text=${input_search_properties.value}&type=${type_property.value}&category=${category_property.value}`);
        }else{
            ctn_properties.innerHTML = "";
        }   
    })
    category_property.addEventListener("change", ()=>{
        if (input_search_properties.value.length > 0 && input_search_properties.value.trim()){
            search_property(`/api/properties?text=${input_search_properties.value}&type=${type_property.value}&category=${category_property.value}`);
        }
    })
    type_property.addEventListener("change", ()=>{
        if (input_search_properties.value.length > 0 && input_search_properties.value.trim()){
            search_property(`/api/properties?text=${input_search_properties.value}&type=${type_property.value}&category=${category_property.value}`);
        }
    })
    window.addEventListener("click", ()=>{
        ctn_properties.innerHTML = "";
    })
}

const search_service_function = () =>{

    const template_result = (address, locality, quantity, type) =>{
        let t = '<a href="/result/services?ps=1&sti='+type+'&address='+address+'" class="link-container">'+
                '    <div class="ctn-address">'+
                '        <h3>'+ address +'</h3>'+
                '    </div>'+
                '    <div class="ctn-others-details">'+
                '        <span class="ctn-location-main">'+
                '            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="" d="M12 2c-4.4 0-8 3.6-8 8c0 5.4 7 11.5 7.3 11.8c.2.1.5.2.7.2s.5-.1.7-.2C13 21.5 20 15.4 20 10c0-4.4-3.6-8-8-8m0 17.7c-2.1-2-6-6.3-6-9.7c0-3.3 2.7-6 6-6s6 2.7 6 6s-3.9 7.7-6 9.7M12 6c-2.2 0-4 1.8-4 4s1.8 4 4 4s4-1.8 4-4s-1.8-4-4-4m0 6c-1.1 0-2-.9-2-2s.9-2 2-2s2 .9 2 2s-.9 2-2 2"/></svg>'+
                '            <span>'+locality+'</span>'+
                '        </span>'+
                '        <span class="ctn-quantity-result">'+ quantity +'</span>'+
                '    </div>'+
                '</a>';
        return t;
    }
    const ctn_properties = document.getElementById("container-main-results-services");
    const input_search_properties = document.getElementById("input-search-address-service");
    const service = document.getElementById("sti");
    
    const route_temp = "/api/services?";
    const search_property = async(route)=>{
        ctn_properties.innerHTML = '<div class="container-loader-search-property"><div class="loader-ui-verse-simple"></div><span>Buscando...</span></div>';
        await fetch(route).then(res => res.json()).then(data =>{
            ctn_properties.innerHTML = "";
            
            if (data.status === 200){
                if (Object.keys(data.province).length >= 0){
                    Object.entries(data.province).forEach(([clave, valor]) =>{
                        const response = template_result(clave, "Provincia", valor, service.value);
                        ctn_properties.insertAdjacentHTML("beforeend", response);    
                    })
                }
                if (Object.keys(data.province).length >= 0){
                    Object.entries(data.data).forEach(([clave, valor]) =>{
                        const response = template_result(clave, "Municipio", valor, service.value);
                        ctn_properties.insertAdjacentHTML("beforeend", response);
                    })
                }
                if (data.data.length == 0 && Object.keys(data.province).length == 0){
                    ctn_properties.innerHTML= '<div class="search-not-found">'+
                                                '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><g fill="none" stroke="#b8aaaa" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="#666666"><path d="M17.5 17.5L22 22m-2-11a9 9 0 1 0-18 0a9 9 0 0 0 18 0"/><path d="M9.492 7.5c-.716.043-1.172.163-1.5.491c-.33.329-.449.785-.492 1.501M12.508 7.5c.716.043 1.172.163 1.5.491c.33.329.449.785.492 1.501m-.008 3.13c-.049.651-.173 1.076-.483 1.387c-.329.328-.785.448-1.501.491m-3.016 0c-.716-.043-1.172-.163-1.5-.491c-.311-.311-.435-.736-.484-1.388"/></g></svg>'+
                                                '<span>No hay resultados</span>'+
                                            '</div>';
                }
            }
        });
    }
    
    input_search_properties.addEventListener("input", async()=>{
        if (input_search_properties.value.length > 0 && input_search_properties.value.trim()){
            search_property(`/api/services?text=${input_search_properties.value}&service_type=${service.value}`);
        }else{
            ctn_properties.innerHTML = "";
        }   
    })
    service.addEventListener("change", ()=>{
        if (input_search_properties.value.length > 0 && input_search_properties.value.trim()){
            search_property(`/api/services?text=${input_search_properties.value}&service_type=${service.value}`);
        }
    })
    window.addEventListener("click", ()=>{
        ctn_properties.innerHTML = "";
    })
}