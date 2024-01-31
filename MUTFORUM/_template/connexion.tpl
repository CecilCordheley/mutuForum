{var:errorMsg}
<form action="{:racine}account.php?act=connexion" method="POST" class="offset-1 col-10">
  <div class="header">
    <h2>Connexion</h2>
  </div>
  <div class="mb-3 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
    <div class="col-sm-10">
      <input type="mail" class="form-control" name="user_mail" id="login_mail">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
    <div class="col-sm-10">
      <input type="password" name="user_mdp" class="form-control" id="inputPassword">
    </div>
  </div>
  <div class="mb-3 row">
    <button class="btn btn-primary">Connexion</button>
  </div>
</form>