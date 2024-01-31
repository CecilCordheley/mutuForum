{var:errorMsg}
<form action="{:racine}account.php?act=updatePwd" method="POST" class="offset-3 col-6">
  <div class="header">
    <h2>Veuillez saisir un nouveau mot de passe</h2>
  </div>
  <div class="mb-3 row">
   
  <div class="mb-3 row">
    <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
    <div class="col-sm-10">
      <input type="password" name="user_mdp" class="form-control" id="inputPassword" required>
    </div>
  </div>
  <div class="mb-3 row">
    <button class="col-sm-2 btn btn-primary">Valider</button>
  </div>
</form>