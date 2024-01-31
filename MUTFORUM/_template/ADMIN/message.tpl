<!--ICI LE TEMPLATE SPECIFIQUE DE VOTRE PAGE-->
<h2>Gestion des Messages</h2>

<div class="row">
    <div class="col-12 col-lg-6">
        <table class="table">
            <tr>
                <th>#</th>
                <th>Propriétaire</th>
                <th>Date</th>
                <th colspan="2">Topic</th>
                <th>Réponses</th>
                <th>DISPLAY</th>
                <th>Actions</th>
            </tr>
            {LOOP:message}
            <tr>
                <td>{#ID_MESSAGE#}</td>
                <td>{#PSEUDO_UTILISATEUR#}</td>
                <td>{#SHORT_DATE#}</td>
                <td>{#ID_SUJET#}</td>
                <td>{#LIB_SUJET#}</td>
                <td>{#REPONSE#}</td>
                <td>{#DISPLAY#}</td>
                <td>
                    <a href="?ID={#ID_MESSAGE#}">Voir ce message</a>
                    <a title="masquer/afficher" href="?act=disable&ID={#ID_MESSAGE#}" class="btn btn-primary"><i class="fa-solid fa-eye-slash"></i></a>
                    <a title="supprimer" href="?act=delete&ID={#ID_MESSAGE#}" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></a>
                </td>
            </tr>
            {/LOOP}
        </table>
    </div>
    <div class="col-12 col-lg-5" name="messageInfo">
        {var:contentMessage}
    </div>
</div>