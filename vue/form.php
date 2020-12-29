<?php if(!isset($_SESSION))
	{
		session_start();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>JSP Page</title>
    </head>
    <body>
        <div class="container" style="padding-top: 15%">
        
      
            <div class="accordion" id="accordionExample">
                <div class="card" style="border-bottom : 1px solid rgb(0,0,0,.125)">
                  <div class="card-header" id="headingOne">
                    <h2 class="mb-0">S'inscrire</h2>
                    <?php
                    //si l'utilisateur n'existe pas et que l'inscription s'est bien passé
                    if(isset($_SESSION['userStateLogUp'])){echo "<h6 style='color:{$_SESSION['userStateLogUp']['couleur']};float : right'>{$_SESSION['userStateLogUp']['res']}</h6>";}  
                    unset($_SESSION['userStateLogUp']);         
                    ?>
                  </div>

                  <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <form action="../controller/controller.php?ctrl=user&fc=logup" method="post" id="formula" name="formula">
                            <div class="form-group row">
                              <label for="inputPseudo" class="col-sm-2 col-form-label">Pseudo</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputPseudo" name="inputPseudo" placeholder="Pseudo">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="inputFirstName" class="col-sm-2 col-form-label">Prénom</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputFirstName" name="inputFirstName" placeholder="Prénom">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="inputLastName" class="col-sm-2 col-form-label">Nom</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputLastName" name="inputLastName" placeholder="Nom">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="inputPassword" class="col-sm-2 col-form-label">Mot de passe</label>
                              <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Mot de passe">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                              <div class="col-sm-10">
                                <input type="email" class="form-control" id="staticEmail" name="staticEmail" placeholder="email@example.com">
                              </div>
                            </div>
                             <button type="submit" name="btnSubmit" id="btnSubmit" class="btn btn-primary">Valider</button>
                          </form>
                    </div>
                  </div>
               </div>
               <div class="card" style="border-bottom : 1px solid rgb(0,0,0,.125)">
                  <div class="card-header" id="headingOne">
                    <h2 class="mb-0">Se connecter</h2>
                    <?php
                    //si l'utilisateur n'existe pas et que l'inscription s'est bien passé
                    if(isset($_SESSION['userStateLogIn'])){echo "<h6 style='color:{$_SESSION['userStateLogIn']['couleur']};float : right'>{$_SESSION['userStateLogIn']['res']}</h6>";}  
                    unset($_SESSION['userStateLogIn']);         
                    ?>
                  </div>
                  <div id="collapseTwo" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <form action="../controller/controller.php?ctrl=user&fc=login" method="post" id="formula" name="formula">
                            <div class="form-group row">
                              <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                              <div class="col-sm-10">
                                <input type="email" class="form-control" id="staticEmail" name="staticEmail" placeholder="email@example.com">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="inputPassword" class="col-sm-2 col-form-label">Mot de passe</label>
                              <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Mot de passe">
                              </div>
                            </div>
                             <button type="submit" name="btnSubmit" id="btnSubmit" class="btn btn-primary">Valider</button>
                        </form>
                    </div>
                  </div>
               </div>
             </div>
        </div>
    </body>
</html>