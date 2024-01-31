function checkMessage() {
    //Récupérer le message
    let message = document.querySelector('#messageInput');
    if (message.value.length==0){
        alert("Votre message est vide !");
        message.classList.add("unvalide");
        return false;
    }else{
        const regex = /<([a-z A-Z]+)>/u;
        if ((m = regex.exec(message.value)) !== null) {
           alert("Vous ne pouvez pas placer de balises html dans votre message !!");
           message.classList.add("unvalide");
           return false;
        }
    }
        return true;
}
function modal(content){
    var _overlay=document.createElement("div");
    _overlay.classList.add("modal_overlay");
    _overlay.onclick=function(){
        this.remove();
    }
    var _content=document.createElement("div");
    _content.innerHTML=content;
    _overlay.appendChild(_content);
    document.body.appendChild(_overlay);
}