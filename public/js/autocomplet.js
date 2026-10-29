function initAutocomplete() {
    let localidadInput = document.getElementById("address");

    // Autocomplete solo para localidades en Barcelona
    let localidadAutocomplete = new google.maps.places.Autocomplete(localidadInput, {
        types: ["geocode"], // Solo localidades
        componentRestrictions: { country: "ES" } // Solo España
    });

    localidadAutocomplete.addListener("place_changed", function () {
        let place = localidadAutocomplete.getPlace();
        if (!place.geometry) {
            return;
        }

        // Extraer la dirección principal de la localidad
        // Inicializar valores
        let via = "";
        let numero = "";
        let ciudad = "";
        let comunidad_autonoma = "";
        let provincia = "";
        let distrito = "";
        let codigoPostal = "";
        let pais = "";
        let lat = "";
        let lng = "";
        // Recorrer los componentes de la dirección
        place.address_components.forEach(component => {
            if (component.types.includes("route")) {
                via = component.long_name; // Nombre de la vía
            }
            if (component.types.includes("street_number")) {
                numero = component.long_name; // Número de la vía
            }
            if (component.types.includes("locality")) {
                ciudad = component.long_name; // Ciudad - municipio en españa
            }
            if (component.types.includes("administrative_area_level_1")) {
                comunidad_autonoma = component.long_name; // Comunidad autonónoma - region o departamento
            }
            if (component.types.includes("administrative_area_level_2")) {
                provincia = component.long_name; // Provincia
            }
            if (component.types.includes("administrative_area_level_3") || component.types.includes("sublocality_level_1")) {
                distrito = component.long_name; // distrito
            }
            if (component.types.includes("postal_code")) {
                codigoPostal = component.long_name; // Código Postal
            }
            if (component.types.includes("country")) {
                pais = component.long_name; // País
            }
        });
        lat = place.geometry.location.lat();
        lng = place.geometry.location.lng();
        
        document.getElementById("city").value = ciudad;
        document.getElementById("province").value = provincia;
        // document.getElementById("postal_code").value = codigoPostal;
        // document.getElementById("country").value = pais;
        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;
        document.getElementById("zoom").value = 11;
        
        const form_filter = document.getElementById("form-filter-result");
        const inputs = form_filter.querySelectorAll("input");
        inputs.forEach((input) =>{
            if (input.value === "" || input.value === "0"){
                input.setAttribute("disabled", true);
            }else{
                input.removeAttribute("disabled");
            }
        });
        form_filter.submit();
        
    });
}

document.addEventListener("DOMContentLoaded", ()=>{
    initAutocomplete();
})