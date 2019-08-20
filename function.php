<?php

  session_start();

  function make_cells_null()
  {
    $_SESSION["game"] = 
    [
      1 => null,
      2 => null,
      3 => null,
      4 => null,
      5 => null,
      6 => null,
      7 => null,
      8 => null,
      9 => null
    ];
    $_SESSION["status"] = "start";
    if(!isset($_SESSION["players_name"]))
      $_SESSION["players_name"] = ["Player1" , "Player2"];
    if(isset($_SESSION["lastwinner"]))
      $_SESSION["starter"] = $_SESSION["lastwinner"];
    else
    {
      if(rand(0,1) == 0)
        $_SESSION["starter"] = 'X';
      else
        $_SESSION["starter"] = 'O';
    }
  }

  function create_game()
  {
    $p1 = $_SESSION['players_name'][0];
    $p2 = $_SESSION['players_name'][1];
    echo "<div class='row'>\n\t";
    echo "<span class='span6'";
    if($_SESSION["starter"] == 'O')
      echo " id='bgturn'";
    echo ">$p1(<span class='cO'>O</span>)</span>\n\t";
    echo"<span class='span6'";
    if($_SESSION["starter"] == 'X')
      echo " id='bgturn'";
    echo">$p2(<span class='cX'>X</span>)</span>\n";
    echo "</div>";
    echo "<br>";
    foreach ($_SESSION["game"] as $cell => $value)
    {
      $class_name = null;
      if($value)
        $class_name = 'c'.$value;
      $output = "<input type='submit' class='cell $class_name' value='$value' name='cell$cell'";
      if($value || check_winner() != null)
        $output = $output . " disabled";
      $output = $output . ">\n";
      echo $output;
    }
  }

  function change_turn()
  {
    if($_SESSION["starter"] == 'O')
      $_SESSION["starter"] = 'X';
    else
      $_SESSION["starter"] = 'O';
  }

  function make_action()
  {
    foreach ($_SESSION["game"] as $cell => $value)
      if(isset($_POST['cell'.$cell]) && $_SESSION["game"][$cell] == null)
      {
        $_SESSION["status"] = "inprogress";
        $_SESSION["game"][$cell] = $_SESSION["starter"];
        change_turn();
      }
  }

  function init()
  {
    if(isset($_POST["start"]) || !isset($_SESSION["game"]))
      make_cells_null();
  }

  function game()
  {
    if(isset($_GET['action']) && $_GET['action'] == 'set_name')
    {
      if(isset($_POST["set_name"]))
      {
        $_SESSION["players_name"][0] = $_POST["player1"];
        $_SESSION["players_name"][1] = $_POST["player2"];
        header("Location:/projects/tictactoe");
      }
      echo set_name();
    }
    else
    {  
      create_game();
      echo get_winner();
      make_resetbtn();
    }
  }

  function check_winner()
  {
    $g = $_SESSION["game"];
    $winner = null;
    if
    (
         ($g[1] && $g[1] == $g[2] && $g[2] == $g[3])
      || ($g[1] && $g[1] == $g[2] && $g[2] == $g[3])
      || ($g[4] && $g[4] == $g[5] && $g[5] == $g[6])
      || ($g[7] && $g[7] == $g[8] && $g[8] == $g[9])
      || ($g[1] && $g[1] == $g[4] && $g[4] == $g[7])
      || ($g[2] && $g[2] == $g[5] && $g[5] == $g[8])
      || ($g[3] && $g[3] == $g[6] && $g[6] == $g[9])
      || ($g[1] && $g[1] == $g[5] && $g[5] == $g[9])
      || ($g[3] && $g[3] == $g[5] && $g[5] == $g[7])
    )
    {
      if($_SESSION["starter"] == 'X')
        $winner = 'O';
      else
        $winner = 'X';
    }
    return $winner;
  }

  function get_winner()
  {
    $change_name = "<p><a href='?action=set_name'>Do you want to save your name?</a></p>";
    if($_SESSION["status"] == "start")
      return null;
    $result = check_winner();
    if($result) 
    {
      $_SESSION["lastwinner"] = $result;
      $_SESSION["status"] = "winner";
      save_result();
      echo "<div id='result'>$result win! $change_name</div>";
    }
    elseif(!in_array(null, $_SESSION["game"]))
    {
      $_SESSION["lastwinner"] = null;
      $_SESSION["status"] = "tie";
      save_result();
      echo "<div id='result'>Tie!$change_name</div>";
    }
    else
    {
      $_SESSION["status"] = "inprogress";
    }
  }

  function make_resetbtn()
  {
    if($_SESSION["status"] == "inprogress")
      echo "<input type='submit' name='start' value='Resign' class='startbtn'>";
    elseif($_SESSION["status"] == "winner" || $_SESSION["status"] == "tie")
      echo "<input type='submit' name='start' value='Play Again!' class='startbtn'>";
  }

  function set_name()
  {
    $el = "<div class='myform'>";
    $el .= "\n\t<input type='text' name='player1' placeholder='Player1'><br>";
    $el .= "\n\t<input type='text' name='player2' placeholder='Player2'><br>";
    $el .= "\n\t<input type='submit' id='subbtn' name='set_name' value='Save Names'>\n</div>";
    return $el;
  }

  function save_result()
  {
    $new_value = ["winner" => null,"tie" => null,"resign" => null,"inprogress" => null];
    $detail_list = ["winner", "tie", "resign", "inprogress"];
    if(isset($_COOKIE["game_detail_total"]))
      $new_value = json_decode($_COOKIE["game_detail_total"], true);
    foreach ($detail_list as $value)
      if(!isset($new_value[$value]))
        $new_value[$value] = 0;
    $new_value[$_SESSION["status"]] ++;
    if(isset($new_value["count"]))
      $new_value["count"] = $new_value["count"] + 1;
    else
      $new_value["count"] = 1;
    var_dump($new_value);
    $new_value = json_encode($new_value);
    setcookie('game_detail_total', $new_value, time() + (86400 * 365));
  }

  init();
  make_action();

?>