<!--ICI LE TEMPLATE SPECIFIQUE DE VOTRE PAGE-->
<h2>Gestion des Mutuelles</h2>
<div class="row">
    <div class="col-5 offset-1">
        <table class="table">
            <tr>
                <th>#</th>
                <th>Raison Social</th>
                <th>Entrée le</th>
    
                <th>Nombre utilisateurs</th>
    
                <th>Actions</th>
            </tr>
            {LOOP:orga}
            <tr>
                <td>{#ID_ORGA#}</td>
                <td>{#NOM_ORGANISATION#}</td>
                <td>{#DATE_ORGANISME#}</td>
                <td>{#NB_USERS#}</td>
    
                <td>
                    <a title="voir utilisateurs" href="?act=seeUSers&ID={#ID_ORGA#}" class="btn btn-yellow"><i class="fa-solid fa-users"></i></a>
                    <a title="autoriser/bannir" href="?act=disable&ID={#ID_ORGA#}" class="btn btn-primary"><i class="fa-solid fa-eye-slash"></i></a>
                    <a title="supprimer" href="?act=delete&ID={#ID_ORGA#}" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></a>
                </td>
            </tr>
            {/LOOP}
        </table>
         <a data-bs-toggle="modal" data-bs-target="#exampleModal"  class="btn btn-success">Ajouter</a>
    </div>
    <div class="col-4">
        <h4>{var:NOM_ORGANISATION}</h4>
        <table class="table">
            <tr>
                <th>#</th>
                <th>PSEUDO</th>
                <th>MAIL</th>
                <th>TYPE</th>
            </tr>

            {LOOP:user}
            <tr>
                <td>{#ID_USER#}</td>
                <td>{#PSEUDO_USER#}</td>
                <td>{#MAIL_USER#}</td>
                <td>{#TYPE_USER#}</td>
            </tr>
            {/LOOP}
        </table>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Nouvelle mutuelle</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {var:addMutu}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>