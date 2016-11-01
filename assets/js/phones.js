
var phonesCounter = Number($("#phonesCounter").val());

function clone() {

    var clone = $(this).parents(".clonedInput").clone(true);
    clone.find(".phone").val("");
    clone.find(".main").val(0);
    //incrementa antes para setar o valor do elemento clonado
    phonesCounter++;
    clone.find(".phone").attr("name", "phones[" + phonesCounter + "][number]");
    clone.find(".main") .attr("name", "phones[" + phonesCounter + "][main]");
    
    
    clone.insertAfter(".clonedInput:last");
}

function remove() {
    var element = $(this);
    var parent = element.parents("tr:first");
    var isMain = Number(parent.find(".main").val());
    if(isMain){
        alert("Você não pode remover o número prinipal.");
        return false;
    }
}

$("button.clone").on("click", clone);
$("button.remove").on("click", remove);

$("select.main").on("change", function() {
    
    var element = $(this);
    var value = Number(element.val());
    
    if (value) {
        
        // Usuario selecionou "SIM"
        //coloca todos como "não" exceto o atual
        $("select.main").not(element).each(function() {
            $(this).val(0);
        });

    } else {

        // Usuario selecionou "NÃO"
        element.val(1);
        alert("Selecione 1 telefone como o contato principal.");
    }
});