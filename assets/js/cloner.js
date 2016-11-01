function cloner(name){
    var Counter = Number($("#"+name+"Counter").val());

    function clone() {

        var clone = $(this).parents(".clonedInput").clone(true);
        clone.find("."+name).val("");
        clone.find(".main").val(0);
        //incrementa antes para setar o valor do elemento clonado
        Counter++;
        clone.find("."+name).attr("name", name+"s[" + Counter + "][number]");
        clone.find(".main") .attr("name", name+"s[" + Counter + "][main]");
        
        
        clone.insertAfter(".clonedInput."+name+":last");
    }

    function remove() {
        var element = $(this);
        var parent = element.parents("tr:first");
        var isMain = Number(parent.find(".main").val());
        if(isMain){
            alert("Você não pode remover o prinipal.");
            return false;
        }
    }

    $("button.clone."+name).on("click", clone);
    $("button.remove."+name).on("click", remove);

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
            alert("Selecione 1 como principal.");
        }
    });
}