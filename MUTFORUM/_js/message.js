/*Fonction spécifique des "messages" */
function setCollapse(){
   
    var triggerheader=document.querySelectorAll("[data-target]");
    triggerheader.forEach((element)=>{
        element.onclick=function(){
            resetCollapse();
            element.classList.remove("active");
            var target=element.getAttribute("data-target");
            document.querySelectorAll(target).forEach(function(el){
                el.classList.add("hidden")
            })
        }
    })
}
function resetCollapse(){
    document.querySelectorAll(".hidden").forEach(function(el){
        el.classList.remove("hidden");
    })
    document.querySelectorAll("[data-target]").forEach(function(el){
        el.classList.add("active");
        
    })
}