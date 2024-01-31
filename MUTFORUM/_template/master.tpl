<!DOCTYPE HTML>
<html lang="fr">
<head>
  <title>Forum des Mutuelles</title>
 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{:racine}_css/style.css">
    <script src="_js/main.js"></script>
</head>
<body>
  <div class="wrapper container-fluid">

    <header>
      <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
          <a class="navbar-brand" href="{:racine}index.php">Accueil</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              {LOOP:menu}<li class="nav-item">
                <a class="nav-link{var:{#PageName#}}" aria-current="page" href="{:racine}{#href#}">{#PageName#}</a>
              </li>{/LOOP}
            </ul>
            <div class="userInfo">{var:connexion}</div>
          </div>
        </div>
      </nav>
    </header>
    <main>
    {var:alert}
    {var:mainContent}
   
    </main>
    <footer>
      <ul class="footerLink">
        <li><a class="link" href="#">Nous contacter</a></li>
        <li><a href="#">Mentions légales</a></li>
        <li><a href="#">Nos partenaires</a></li>
      </ul>
       {var:admin}
    </footer>
    {var:test}
    
  </div>
</body>
</html>