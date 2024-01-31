<!--ICI LE TEMPLATE SPECIFIQUE DE VOTRE PAGE-->
{var:ResultMessage}
<div data-id_topic="{var:SUJET.ID_SUJET}" id="sujet">
    <h2>{var:SUJET.LIB_SUJET}</h2>
    {view:messageByTopic}
</div>
{var:postMessage}