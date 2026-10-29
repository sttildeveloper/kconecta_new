
// funciona con los inputs numericos, es para aumentar y disminuir el valor de 
// un input numerico con botones de + y -
const add_rest = (id_rest, id_input, id_sum, limit_inf = 0, limit_sup = 100) =>{
    const rest_ = document.getElementById(id_rest);
    const sum_ = document.getElementById(id_sum);
    const number_of_plants = document.getElementById(id_input);
    rest_.addEventListener("click", ()=>{
        if (parseInt(number_of_plants.value) > limit_inf){number_of_plants.value = parseInt(number_of_plants.value) - 1;}
    })
    sum_.addEventListener("click", ()=>{
        if (!number_of_plants.value){number_of_plants.value = 0;}
        if (parseInt(number_of_plants.value) < limit_sup){number_of_plants.value = parseInt(number_of_plants.value) + 1;}
    })
}
// add_rest("_rest", "", "_sum");