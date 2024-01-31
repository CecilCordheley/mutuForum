<!--ICI LE TEMPLATE SPECIFIQUE DE VOTRE PAGE-->
<h2>Gestion des utilisateurs</h2>
<div class="row">
    <div class="col-7 offset-2">
        <table class="table">
            <tr>
                <th>#</th>
                <th>Pseudo</th>
                <th>Mail</th>
                <th>Arrivée</th>
                <th colspan="2">Statut</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
            {LOOP:user}
            <tr>
                <td>{#ID_USER#}</td>
                <td>{#PSEUDO_USER#}</td>
                <td>{#MAIL_USER#}</td>
                <td>{#ARRIVE_UTILISATEUR#}</td>
                <td>{#ID_STATUT#}</td>
                <td>{#LIBELLE_STATUT#}</td>
                <td>{#LIBELLE_TYPE#}</td>
                <td>
                    <a class="btn btn-danger" href="?act=ban&ID={#ID_USER#}"><i class="fa-solid fa-ban"></i></a>
                    <a href="?act=timeout&ID={#ID_USER#}" class="btn btn-warning"><i
                            class="fa-solid fa-user-clock"></i></a>
                    <a href="?act=allowed&ID={#ID_USER#}" class="btn btn-success"><i
                            class="fa-solid fa-user-check"></i></a>
                    <a href="?act=seeUser&ID={#ID_USER#}"  class="btn btn-primary">
                        <i class="fa-regular fa-id-card"></i>
                    </a>
                </td>
            </tr>
            {/LOOP}
        </table>
        <a data-bs-toggle="modal" data-bs-target="#exampleModal"  class="btn btn-success">Ajouter</a>
    </div>
</div>
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fs-5" id="exampleModalLabel">{var:seeUser.PSEUDO_USER}</h3>
                <a class="btn btn-close" href="{:racine}ADMIN_user.php"></a>
            </div>
            <div class="modal-body">
                <span>appartient à {var:seeUser.ORGANISATION}</span>
                <span>Il a posté : {var:seeUser.NBMESSAGE} messages</span>
                <span>Il a créé son compte le : {var:seeUser.ARRIVE_UTILISATEUR}</span>
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary" href="{:racine}ADMIN_user.php">Close</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Nouvel Utilisateur</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {var:addUser}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>