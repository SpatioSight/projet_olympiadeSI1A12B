<html>
<head>
  <link rel="stylesheet" href="../../css/index.css"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
</head>

<body>
  <?php session_start();
  $_SESSION['connect']=0;
  if(!isset($_SESSION['loginOK'])){
    header('Location: ../protection/connexion.php');
  }?>

  <?php
    include 'menu_admin.php';
    include '../Interactions/InteractionsBD.php';
    include '../Interactions/Connexion.php';

    $db = connect_database();
  ?>

  <center><h1>Résultats finaux</h1></center>

  <div>
    <div style="padding-top: 1% ; padding-left: 5% ; padding-right: 5%">
      <table class="table table-striped" style="text-align: center;">
        <thead class="thead-dark">
          <tr>
            <th>Équipe</th>
            <th>Originalité</th>
            <th>Prototype</th>
            <th>Démarche SI</th>
            <th>Pluridisciplinarité</th>
            <th>Maîtrise</th>
            <th>Développement Durable</th>
            <th>Moyenne</th>
          </tr>
        </thead>
        <?php
        $all_grp = array();
        $all_recompense = array();
        try{
          $stmt = $db->prepare("SELECT NumGroupe FROM GROUPE where NumGroupe != 0");
          $stmt->execute();
          $stmt2 = $db->prepare("SELECT idGroupe, idRecompense FROM RECOMPENSE");
          $stmt2->execute();

          while($row = $stmt2->fetch()){
            $all_recompense[$row["idRecompense"]] = $row["idGroupe"];
          }

          $rec0 = NULL;
          $rec1 = NULL;
          $rec2 = NULL;
          $rec3 = NULL;
          $rec4 = NULL;
          $rec5 = NULL;
          $rec6 = NULL;

          while($row = $stmt->fetch()){
            array_push($all_grp,$row['NumGroupe']);
            $liste = getNote($db,$row['NumGroupe']);
            echo "<tr>";
            echo "<td>".$row['NumGroupe']."</td>";

            if(is_nan($liste['Originalite'])){
              $rec0+=1;
              if ($rec0 != $all_recompense["NULL"]){
                echo "<td>". "/" ."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>". "/" ."</td>";
              }
            }

            if(is_nan($liste['Prototype'])){
              $rec1+=1;
              if ($rec1 != $all_recompense["1"]){
                echo "<td>". "/" ."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>". "/" ."</td>";
              }
            }

            if(is_nan($liste['DemarcheSI'])){
              $rec2+=1;
              if ($rec2 != $all_recompense["2"]){
                echo "<td>". "/" ."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>". "/" ."</td>";
              }
            }

            if(is_nan($liste['pluriDisciplinarite'])){
              $rec3+=1;
              if ($rec3 != $all_recompense["3"]){
                echo "<td>". "/" ."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>". "/" ."</td>";
              }
            }

            if(is_nan($liste['Maitrise'])){
              $rec4+=1;
              if ($rec4 != $all_recompense["4"]){
                echo "<td>". "/" ."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>". "/" ."</td>";
              }
            }

            if(is_nan($liste['devDurable'])){
              $rec5+=1;
              if ($rec5 != $all_recompense["5"]){
                echo "<td>". "/" ."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>". "/" ."</td>";
              }
            }

            if(is_nan($liste['Originalite']) OR is_nan($liste['Prototype']) OR is_nan($liste['DemarcheSI']) OR is_nan($liste['pluriDisciplinarite']) OR is_nan($liste['Maitrise']) OR is_nan($liste['devDurable'])){
              $rec6+=1;
              if ($rec6 != $all_recompense["6"]){
                echo "<td>". "/" ."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>". "/" ."</td>";
              }
            }

            else{
              $rec0+=1;
              if ($rec0 != $all_recompense["NULL"]){
                echo "<td>".$liste['Originalite']."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>".$liste['Originalite']."</td>";
              }

              $rec1+=1;
              if ($rec1 != $all_recompense["1"]){
                echo "<td>".$liste['Prototype']."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>".$liste['Prototype']."</td>";
              }

              $rec2+=1;
              if ($rec2 != $all_recompense["2"]){
                echo "<td>".$liste['DemarcheSI']."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>".$liste['DemarcheSI']."</td>";
              }

              $rec3+=1;
              if ($rec3 != $all_recompense["3"]){
                echo "<td>".$liste['pluriDisciplinarite']."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>".$liste['pluriDisciplinarite']."</td>";
              }

              $rec4+=1;
              if ($rec4 != $all_recompense["4"]){
                echo "<td>".$liste['Maitrise']."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>".$liste['Maitrise']."</td>";
              }

              $rec5+=1;
              if ($rec5 != $all_recompense["5"]){
                echo "<td>".$liste['devDurable']."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>".$liste['devDurable']."</td>";
              }

              $rec6+=1;
              if ($rec6 != $all_recompense["6"]){
                echo "<td>".round(($liste['Originalite'] + $liste['Prototype'] + $liste['DemarcheSI'] + $liste['pluriDisciplinarite'] + $liste['Maitrise'] + $liste['devDurable'])/6, 2) ."</td>";
              }
              else{
                echo "<td bgcolor='#FFE469'>".round(($liste['Originalite'] + $liste['Prototype'] + $liste['DemarcheSI'] + $liste['pluriDisciplinarite'] + $liste['Maitrise'] + $liste['devDurable'])/6, 2) ."</td>";
              }
            }

            echo "</tr>";
          }
        }
        catch(Exception $e){
          echo $e.getMessage();
        }
        ?>
      </table>
    </div>

    <div style:"display: flex">
      <div style="background-color: #FFE469 ; height: 25px ; width: 30px ; float: left ; margin-left: 5%"> </div>
      <label style="padding-left: 1%">Attribution actuelle des prix</label>
    </div>

    <div style="padding-left: 5% ; padding-right: 5% ; padding-top: 5%">
      <h3>Attribution des prix :</h3>
      <form method="post" action="insert_res.php">
        <table class="table">
          <tr>
            <td>Originalité</td>
            <td>
              <select name="originalite">
                <option value="none"></option>
                <?php
                  foreach ($all_grp as $num) {
                    if ($all_recompense["NULL"]==$num){
                      echo "<option value='".$num."' selected='selected'>Groupe ".$num."</option>";
                    }
                    else{
                      echo "<option value='".$num."'>Groupe ".$num."</option>";
                    }
                  }?>
              </select>
            </td>
            <td>Prototype</td>
            <td>
              <select name="prototype">
                <option value="none"></option>
                <?php foreach ($all_grp as $num) {
                  if ($all_recompense["1"]==$num){
                    echo "<option value='".$num."' selected='selected'>Groupe ".$num."</option>";
                  }
                  else{
                    echo "<option value='".$num."'>Groupe ".$num."</option>";
                  }
                } ?>
              </select>
            </td>
            <td>Démarche SI</td>
            <td>
              <select name="demarche_si">
                <option value="none"></option>
                <?php foreach ($all_grp as $num) {
                  if ($all_recompense["2"]==$num){
                    echo "<option value='".$num."' selected='selected'>Groupe ".$num."</option>";
                  }
                  else{
                    echo "<option value='".$num."'>Groupe ".$num."</option>";
                  }
                } ?>
              </select>
            </td>
          </tr>

          <tr>
            <td>Pluridisciplinarité</td>
            <td>
              <select name="pluridisciplinarite">
                <option value="none"></option>
                <?php foreach ($all_grp as $num) {
                  if ($all_recompense["3"]==$num){
                    echo "<option value='".$num."' selected='selected'>Groupe ".$num."</option>";
                  }
                  else{
                    echo "<option value='".$num."'>Groupe ".$num."</option>";
                  }
                } ?>
              </select>
            </td>
            <td>Maîtrise</td>
            <td>
              <select name="maitrise">
                <option value="none"></option>
                <?php foreach ($all_grp as $num) {
                  if ($all_recompense["4"]==$num){
                    echo "<option value='".$num."' selected='selected'>Groupe ".$num."</option>";
                  }
                  else{
                    echo "<option value='".$num."'>Groupe ".$num."</option>";
                  }
                } ?>
              </select>
            </td>
            <td>Développement durable</td>
            <td>
              <select name="developpement_durable">
                <option value="none"></option>
                <?php foreach ($all_grp as $num) {
                  if ($all_recompense["5"]==$num){
                    echo "<option value='".$num."' selected='selected'>Groupe ".$num."</option>";
                  }
                  else{
                    echo "<option value='".$num."'>Groupe ".$num."</option>";
                  }
                } ?>
              </select>
            </td>
          </tr>

          <tr>
            <td>Moyenne</td>
            <td>
              <select name="moyenne">
                <option value="none"></option>
                <?php foreach ($all_grp as $num) {
                  if ($all_recompense["6"]==$num){
                    echo "<option value='".$num."' selected='selected'>Groupe ".$num."</option>";
                  }
                  else{
                    echo "<option value='".$num."'>Groupe ".$num."</option>";
                  }
                } ?>
              </select>
            </td>
          </tr>
        </table>
        <center>
          <div>
            <input class="btn btn-dark" type="submit" value="Attribuer"/>
          </div>
        </center>
      </form>
    </div>
  </div>

</body>
</html>
