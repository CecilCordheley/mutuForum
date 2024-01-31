<h3>Message de {var:message.USER}<a href="{:racine}ADMIN_user.php?act=seeUser&ID={var:message.ID_USER}">Voir cet utilisateur</a></h3>
<span>posté le {var:message.SHORT_DATE}</span>
<span>Sur le topic : <strong>{var:message.LIB_SUJET}</strong> #{var:message.ID_SUJET}</span>
<p class="contentMessage">{var:message.CONTENT_MESSAGE}</p>
<h4>Réponses associées au message</h4>
<table class="table">
    <tr>
        <th>FROM</th>
        <th>DATE</th>
        <th>CONTENT</th>
        <th>DISPLAY</th>
        <th>Actions</th>
    </tr>
    {LOOP:reponse}
    <tr>
        <td>{#USER#}</td>
        <td>{#SHORT_DATE#}</td>
        <td>{#CONTENT_REPONSE#}</td>
        <td>{#DISPLAY#}</td>
        <td><a href="?act=displayReponse&ID={var:message.ID_MESSAGE}&user={#ID_USER#}&DATE={#DATE_REPONSE#}"><i class="fa-solid fa-eye-slash"></i></a></td>
    {/LOOP}
</table>
