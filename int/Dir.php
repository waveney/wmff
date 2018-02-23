<?php
  include_once("fest.php");
  A_Check('Staff','Docs');
?>

<html>
<head>
<title>WMFF Staff | Document Storage</title>
<?php include("files/header.php"); ?>
<?php include_once("festcon.php"); ?>
<script>
  function uploaddocs(dir) {
    location.href="Dir.php?";
  }
</script>
</head>
<body>
<?php
  include("files/navigation.php"); 
  include("DocLib.php");
  global $USERID;

  echo '<div class="content">';

//  echo var_dump($_COOKIE) . "<P>";
// if ($_POST) var_dump($_POST);


  if (isset($_GET{'d'})) {
    $d = $_GET{'d'};
  } elseif (isset($_POST{'d'})) {
    $d = $_POST{'d'};
  } else {
    $d = 0;
  }

  $dir  = Get_DirInfo($d);
  $list = Get_DirList($d);
  $subs = Get_SubDirList($d);
  $AllU = Get_AllUsers(0);
  $AllA = Get_AllUsers(1);
  $AllActive = array();
  foreach ($AllU as $id=>$name) if ($AllA[$id] >= 2 && $AllA[$id] <= 6) $AllActive[$id]=$name;

  if (isset($_POST{'Action'})) { $Act = $_POST{'Action'}; }
  elseif (isset($_GET{'Action'})) { $Act = $_GET{'Action'}; }
  else { $Act = ''; }
  $skip = 0;
  
  switch ($Act) {
    case 'Create':
      $NewDir = $_POST{'DirName'};
      if (strlen($NewDir) < 2) {
        $ErrMess = "Directory name too short"; 
      } else {
        $ndir = "Store" . Dir_FullName($d) . "/" . $NewDir;
        if (file_exists($ndir)) {
	  $ErrMess = $NewDir . " already exists";
        } else {
	  umask(0);
	  $ans = mkdir ($ndir,0777,1);
	  $newrec = "INSERT INTO Directories SET Name='" . addslashes($NewDir) . "', who=$USERID, Created=" . time() . ", Parent=" . $d;
          $res = $db->query($newrec); 
  	  $subs = Get_SubDirList($d); // Refresh list
	}
      }  
      break;
    case 'Delete':
      /* delete all files and directories (recurse) set d to Parent then do dir, log it */
      if ($d > 0 && (Access('Committee','Docs') || $dir['who'] == $USERID || $sub['who'] == $USERID )) {
        $Parent = $dir['Parent'];
        DeleteAll($d);
        $d = $Parent;
        $dir  = Get_DirInfo($d);
        $list = Get_DirList($d);
        $subs = Get_SubDirList($d);
      } else {
        $ErrMess = "Insufficient Priviledge";
      }
      break;
    case 'Rename1':
      if ($d > 0 && (Access('Committee','Docs') || $dir['who'] == $USERID || $sub['who'] == $USERID )) {
  	echo '<form action="Dir.php" method="post">';
  	echo fm_hidden('d', $d);
        echo "<h2>Rename " . htmlspec($dir['SName']) . "</h2>";
  	echo fm_simpletext('as new name ',$_POST,'DirName');
  	echo '<input type="submit" value="Rename" name="Action">';
  	echo "</form>\n";
	$skip = 1;
      } else {
        $ErrMess = "Insufficient Priviledge";
      }
      break;
    case 'Rename':
      if ($d > 0 && (Access('Committee','Docs') || $dir['who'] == $USERID || $sub['who'] == $USERID )) {
        $NewDir = $_POST{'DirName'};
	if ($dir['SName'] == $NewDir) break;
	$fullname = Dir_FullName($d);
  	$fullpath = dirname($fullname);
        if (file_exists("Store" . $fullpath . "/" . $NewDir)) {
          $ErrMess = "Directory already exists";
 	  break;
	}
	rename("Store" . $fullname, "Store" . $fullpath . "/" . $NewDir);
 	$dir['SName'] = addslashes($NewDir);
        Put_DirInfo($dir);
      } else {
        $ErrMess = "Insufficient Priviledge";
      }
      break;
    case 'Move1':
      if ($d > 0 && (Access('Committee','Docs') || $dir['who'] == $USERID || $sub['who'] == $USERID )) {
  	echo '<form action="Dir.php" method="post">';
  	echo fm_hidden('d', $d);
        echo "<h2>Move " . htmlspec($dir['SName']) . " to </h2>";
  	echo Dir_All_Tree('NewDir',$dir['Parent'],$d);
  	echo '<input type="submit" value="Move" name="Action">';
  	echo "</form>\n";
	$skip = 1;
      } else {
        $ErrMess = "Insufficient Priviledge";
      }
      break;
    case 'Move':
      if ($d > 0 && (Access('Committee','Docs') || $dir['who'] == $USERID || $sub['who'] == $USERID )) {
        $NewDir = $_POST{'NewDir'};
	$name = $dir['SName'];
	if ($dir['Parent'] == $NewDir) break;
	$fullname = Dir_FullName($d);
  	$fullpath = dirname($fullname);
	$Newpath = Dir_FullName($NewDir);
        if (file_exists("Store" . $Newpath . "/" . $name)) {
          $ErrMess = "Directory already exists";
 	  break;
	}
	rename("Store" . $fullname, "Store" . $Newpath . "/" . $name);
 	$dir['Parent'] = $NewDir;
        Put_DirInfo($dir);
      } else {
        $ErrMess = "Insufficient Priviledge";
      }
      break;
    case 'Chown1':
      if ($d > 0 && Access('Committee','Docs')) {
  	echo '<form action="Dir.php" method="post">';
  	echo fm_hidden('d', $d);
        echo "<h2>Change Ownership of " . htmlspec($dir['SName']) . " to </h2>";
  	echo fm_select($AllActive,$dir,'Who');
  	echo '<input type="submit" value="Chown" name="Action">';
  	echo "</form>\n";
	$skip = 1;
      } else {
        $ErrMess = "Insufficient Priviledge";
      }
      break;
    case 'Chown':
      if ($d > 0 && Access('Committee','Docs')) {
 	$dir['Who'] = $_POST{'Who'};
        Put_DirInfo($dir);
      } else {
        $ErrMess = "Insufficient Priviledge";
      }
      break;
    default:
  }

  if (isset($_POST{'FileAction'})) { $Act = $_POST{'FileAction'}; }
  elseif (isset($_GET{'FileAction'})) { $Act = $_GET{'FileAction'}; }
  else { $Act = ''; }
  
  if (!$skip && $Act) {
    if (isset($_GET{'f'})) {
      $f = $_GET{'f'};
    } elseif (isset($_POST{'f'})) {
      $f = $_POST{'f'};
    } else {
      $f = 0;
    };
    $finf = Get_DocInfo($f);

//echo var_dump($finf) . "<p>";

    switch ($Act) {
      case 'Rename1':
        if (Access('Committee','Docs') || $finf['who'] == $USERID || $dir['who'] == $USERID ) {
  	  echo '<form action="Dir.php" method="post">';
  	  echo fm_hidden('d', $d) . fm_hidden('f', $f);
          echo "<h2>Rename " . htmlspec($finf['SName']) . "</h2>";
  	  echo fm_simpletext('as new name ',$_POST,'DocName');
  	  echo '<input type="submit" value="Rename" name="FileAction">';
  	  echo "</form>\n";
	  $skip = 1;
        } else {
          $ErrMess = "Insufficient Priviledge";
        }
	break;
      case 'Rename':
        if (Access('Committee','Docs') || $finf['who'] == $USERID || $dir['who'] == $USERID ) {
          $NewDoc = $_POST{'DocName'};
	  if ($finf['SName'] == $NewDoc) break;
	  $fullname = File_FullName($f);
  	  $fullpath = dirname($fullname);
          if (file_exists("Store" . $fullpath . "/" . $NewDoc)) {
            $ErrMess = "Document already exists";
   	    break;
	  }
	  rename("Store" . $fullname, "Store" . $fullpath . "/" . $NewDoc);
 	  $finf['SName'] = addslashes($NewDoc);
          Put_DocInfo($finf);
        } else {
          $ErrMess = "Insufficient Priviledge";
        }
	break;
      case 'Move1':
        if (Access('Committee','Docs') || $finf['who'] == $USERID || $dir['who'] == $USERID ) {
  	  echo '<form action="Dir.php" method="post">';
  	  echo fm_hidden('d', $d) . fm_hidden('f', $f);
          echo "<h2>Move " . htmlspec($finf['SName']) . " to </h2>";
  	  echo Dir_All_Tree('NewDir',$finf['Dir']);
  	  echo '<input type="submit" value="Move" name="FileAction">';
  	  echo "</form>\n";
	  $skip = 1;
        } else {
          $ErrMess = "Insufficient Priviledge";
        }
	break;
      case 'Move':
        if (Access('Committee','Docs') || $finf['who'] == $USERID || $dir['who'] == $USERID ) {
          $NewDir = $_POST{'NewDir'};
	  $name = $finf['SName'];
	  if ($finf['Dir'] == $NewDir) break;
	  $fullname = File_FullName($f);
	  $Newpath = Dir_FullName($NewDir);
          if (file_exists("Store" . $Newpath . "/" . $name)) {
            $ErrMess = "Document already exists";
 	    break;
	  }
	  rename("Store" . $fullname, "Store" . $Newpath . "/" . $name);
 	  $finf['Dir'] = $NewDir;
	  Put_DocInfo($finf);
        } else {
          $ErrMess = "Insufficient Priviledge";
        }
	break;
      case 'Chown1':
        if (Access('Committee','Docs')) {
  	  echo '<form action="Dir.php" method="post">';
  	  echo fm_hidden('d', $d) . fm_hidden('f', $f);
          echo "<h2>Change Ownership of " . htmlspec($finf['SName']) . " to </h2>";
  	  echo fm_select($AllActive,$finf,'Who');
  	  echo '<input type="submit" value="Chown" name="FileAction">';
  	  echo "</form>\n";
	  $skip = 1;
        } else {
          $ErrMess = "Insufficient Priviledge";
        }
	break;
      case 'Chown':
        if (Access('Committee','Docs')) {
 	  $finf['Who'] = $_POST{'Who'};
          Put_DocInfo($finf);
        } else {
          $ErrMess = "Insufficient Priviledge";
        }
	break;
      case 'Delete':
        if (Access('Committee','Docs') || $finf['who'] == $USERID || $dir['who'] == $USERID ) {
          DeleteFile($f);
        } else {
          $ErrMess = "Insufficient Priviledge";
        }
	break;
      case 'Upload Document(s)':
      case 'Upload':

        $target_dir = "Store" . Dir_FullPname($d);
	// Count # of uploaded files in array
	$total = count($_FILES['uploads']['name']);

	// Loop through each file
	for($i=0; $i<$total; $i++) {
	  //Get the temp file path
	  $tmpFilePath = $_FILES['uploads']['tmp_name'][$i];

	  //Make sure we have a filepath
	  if ($tmpFilePath != ""){
	    //Setup our new file path
	    $file = basename($_FILES['uploads']['name'][$i]);
            $target_file = $target_dir . "/" . $file;
//var_dump($tmpFilePath,$target_file);
	    //Upload the file into the temp dir
	    if(move_uploaded_file($tmpFilePath, $target_file)) {
 	      //Handle other code here
	      Doc_Create($file,$d,filesize($target_file));
	    } else $ErrMess = "File failed to move to the Store";
	  }
	}

/*
        $file = basename($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir . "/" . $file;
        if (move_uploaded_file($_FILES["fileToUploads"]["tmp_name"], $target_file)) Doc_Create($file,$d,filesize($target_file));
*/
        $list = Get_DirList($d);
        break;
      default:
    }
    $list = Get_DirList($d);
  }


// List Dir & link to parent (if poss) & top (if not top) list of stuff (sortable) links to add doc, new dir
// Each file - click to open, dirs click to follow, each file (if suf priv, links to rename, delete, move  on files and dirs)

  if (isset($ErrMess)) echo "<h2 class=ERR>$ErrMess</h2>\n";

// echo var_dump($dir) . "<p>";

  if (!$skip) {
    echo "<h2>Directory: " . Get_Parent($d) . "</h2>";
    Doc_Table_Head();
  
    if ($d > 0) {
      $pid = $dir['Parent'];
      $pdir = Get_DirInfo($pid);

      if ($pid > 0) { $name = htmlspec($pdir['SName']); }
      else {$name = 'Documents'; };
  
      echo "<tr><td><a href=Dir.php?d=$pid>$name</a>";
      echo "<td>". $AllU[$pdir['Who'] || 1];
      echo "<td>Parent";
      echo "<td>";
      if (isset($pdir['Created'])) echo date('d/m/y H:i:s',$pdir['Created']);
//      echo "<td>" . Doc_Access($pdir['Access']);
      echo "<td>";
      if ($pid > 0 && (Access('Committee','Docs') || $pdir['who'] == $USERID || $pdir['who'] == $USERID )) {
        echo " <a href=Dir.php?d=$pid&Action=Rename1>Rename</a>"; 
        echo " <a href=Dir.php?d=$pid&Action=Move1>Move</a>"; 
        echo " <a href='Dir.php?d=$pid&Action=Delete' onClick=\"javascript:return confirm('are you sure you want to delete this?');\">Delete</a>"; 
        if (Access('Committee','Docs')) {
          echo " <a href=Dir.php?d=$pid&Action=Chown1>Chown</a>"; 
        }
      }
    }

    if ($subs) {
      foreach($subs as $sub) {
        $pid = $sub['DirId'];
        echo "<tr><td><a href=Dir.php?d=$pid>" . htmlspec($sub['SName']) . "</a>";
        echo "<td>" . $AllU[$sub['Who']];
        echo "<td>Directory";
        echo "<td>" . date('d/m/y H:i:s',$sub['Created']) . "<td>";
//	echo Doc_Access($sub['Access']) . "<td>";
        if (Access('Committee','Docs') || $dir['who'] == $USERID  || $sub['who'] == $USERID ) {
          echo " <a href=Dir.php?d=$pid&Action=Rename1>Rename</a>"; 
          echo " <a href=Dir.php?d=$pid&Action=Move1>Move</a>"; 
          echo " <a href='Dir.php?d=$pid&Action=Delete' onClick=\"javascript:return confirm('are you sure you want to delete this?');\">Delete</a>"; 
          if (Access('Committee','Docs')) {
            echo " <a href=Dir.php?d=$pid&Action=Chown1>Chown</a>"; 
          }
        }
      }
    }

    foreach($list as $file) Doc_List($file);
    
    echo "</tbody></table>\n";

    echo '<form action="Dir.php" method="post" enctype="multipart/form-data" id=Uploads>';
    echo "Select file(s) to upload:";
    echo fm_hidden('d', $d);
    echo fm_hidden('FileAction', 'Upload');
    echo '<input type="file" name="uploads[]" multiple onchange=this.form.submit()>';
    echo "</form>\n";

    echo '<form action="Dir.php" method="post">';
    echo fm_hidden('d', $d);
    echo fm_simpletext('New Directory',$_POST,'DirName');
    echo '<input type="submit" value="Create" name="Action">';
    echo "</form>\n";

    SearchForm();
  }
?>

</div>

<?php include("files/footer.php"); ?>
</body>
</html>

