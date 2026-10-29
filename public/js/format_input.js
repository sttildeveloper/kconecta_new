
const format_1 = (id_input, type="id") => {
    // 1,000,000
    let inputMonto = "";
    if (type === "element"){
        inputMonto = id_input;
    }else{
        inputMonto = document.getElementById(id_input);
    }
    
    if (inputMonto){
        if (inputMonto.tagName.toLocaleLowerCase() === "input"){
            const formatterInput = () =>{
                // Obtener el valor del input
                let valor = inputMonto.value;
        
                // Eliminar comas y otros caracteres no numéricos
                valor = valor.replace(/[^0-9]/g, '');
                // Si el valor está vacío, dejar el input vacío
                if (valor === '') {
                    inputMonto.value = '';
                    return;
                }
        
                // Convertir a número y formatear con separadores de miles
                const valorFormateado = Number(valor).toLocaleString("de-DE");
        
                // Asignar el valor formateado al input
                inputMonto.value = valorFormateado;
            }
            inputMonto.addEventListener('input', function (event) {
                formatterInput();
            });
            formatterInput();
        }else if(inputMonto.tagName.toLocaleLowerCase() === "span" || inputMonto.tagName.toLocaleLowerCase() === "p"){
            // Obtener el valor del input
            let valor = inputMonto.textContent;
        
            // Eliminar comas y otros caracteres no numéricos
            valor = valor.replace(/[^0-9]/g, '');
            // Si el valor está vacío, dejar el input vacío
            if (valor === '') {
                inputMonto.textContent = '';
                return;
            }
    
            // Convertir a número y formatear con separadores de miles
            const valorFormateado = Number(valor).toLocaleString("de-DE");
    
            // Asignar el valor formateado al input
            inputMonto.textContent = valorFormateado;
        }else{
            console.error("Elemento "+inputMonto.tagName.toLocaleLowerCase()+" no soportado.");
        }
    }else{
        console.error("No se encontró ningun elemento para formatear.");
    }


}

const format_2 = (id_input, nd = 2) =>{
    // 1,000,000.00
    const inputMonto = document.getElementById(id_input);

    inputMonto.addEventListener('input', function (event) {
        let valor = event.target.value;

        // Permitir números y un solo punto decimal
        valor = valor.replace(/[^0-9.]/g, '');
        valor = valor.replace(/(\..*)\./g, '$1'); // Evitar múltiples puntos
        
        // Si el valor está vacío, dejar el input vacío
        if (valor === '') {
            event.target.value = '';
            return;
        }


        // Separar la parte entera y la decimal
        const partes = valor.split('.');
        let parteEntera = partes[0];
        let parteDecimal = partes.length > 1 ? `.${partes[1]}` : '';

        // Limitar la parte decimal a 2 dígitos
        if (parteDecimal.length > nd + 1) { // 3 porque incluye el punto (.)
            parteDecimal = parteDecimal.substring(0, nd + 1); // Limita a 2 decimales
        }

        // Formatear la parte entera
        parteEntera = Number(parteEntera).toLocaleString("de-DE");

        // Unir las partes formateadas
        event.target.value = parteEntera + parteDecimal;
    });
}