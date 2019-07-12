<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("Document Storage",["js/dropzone.js","css/dropzone.css"]);
  echo '<script> function uploaddocs(dir) { location.href="Dir.php?"; } </script>';

//  echo var_dump($_COOKIE) . "<P>";
// if ($_POST) var_dump($_POST);

  include_once("DocLib.php");
  if (isset($_REQUEST{'d'})) {
    $d = $_REQUEST{'d'};
  } else {
    $d = 0;
  }

//  $DBG = fopen("LogFiles/XLOG","a+");
  
  $dir  = Get_DirInfo($d);
  $list = Get_DirList($d);
  $subs = Get_SubDirList($d);
  $AllU = Get_AllUsers(0);
  $AllA = Get_AllUsers(1);
  $AllActive = array();
  foreach ($AllU as $id=>$name) if ($AllA[$id] >= 2 && $AllA[$id] <= 6) $AllActive[$id]=$name;

  if (isset($_REQUEST{'Action'})) { $Act = $_REQUEST{'Action'}; }
  else { $Act = ''; }
  $skip = 0;
  
  switch ($Act) {
    case 'Create': 
      if ($dir) {
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
            $newrec = array('SN'=>addslashes($NewDir), 'Who'=>$USERID, 'Created'=>time(), 'Parent'=> $d);
            Insert_db('Directories',$newrec);
            $subs = Get_SubDirList($d); // Refresh list
          }
        }
      } else {
        $ErrMess = "Insufficient Priviledge";      
      }  
      break;
    case 'Delete':
      /* delete all files and directories (recurse) set d to Parent then do dir, log it */
      if ($d > 0 && (Access('Committee','Docs') || $dir['Who'] == $USERID || $sub['Who'] == $USERID )) {
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
      if ($d > 0 && (Access('Committee','Docs') || $dir['Who'] == $USERID || $sub['Who'] == $USERID )) {
          echo '<form action="Dir" method="post">';
          echo fm_hidden('d', $d);
        echo "<h2>Rename " . htmlspec($dir['SN']) . "</h2>";
          echo fm_simpletext('as new name ',$_POST,'DirName');
          echo '<input type="submit" value="Rename" name="Action">';
          echo "</form>\n";
        $skip = 1;
      } else {
        $ErrMess = "Insufficient Priviledge";
      }
      break;
    case 'Rename':
      if ($d > 0 && (Access('Committee','Docs') || $dir['Who'] == $USERID || $sub['Who'] == $USERID )) {
        $NewDir = $_POST{'DirName'};
        if ($dir['SN'] == $NewDir) break;
        $fullname = Dir_FullName($d);
          $fullpath = dirname($fullname);
        if (file_exists("Store" . $fullpath . "/" . $NewDir)) {
          $ErrMess = "Directory already exists";
           break;
        }
        rename("Store" . $fullname, "Store" . $fullpath . "/" . $NewDir);
         $dir['SN'] = addslashes($NewDir);
        Put_DirInfo($dir);
      } else {
        $ErrMess = "Insufficient Priviledge";
      }
      break;
    case 'Move1':
      if ($d > 0 && (Access('Committee','Docs') || $dir['Who'] == $USERID || $sub['Who'] == $USERID )) {
          echo '<form action="Dir" method="post">';
          echo fm_hidden('d', $d);
        echo "<h2>Move " . htmlspec($dir['SN']) . " to </h2>";
          echo Dir_All_Tree('NewDir',$dir['Parent'],$d);
          echo '<input type="submit" value="Move" name="Action">';
          echo "</form>\n";
        $skip = 1;
      } else {
        $ErrMess = "Insufficient Priviledge";
      }
      break;
    case 'Move':
      if ($d > 0 && (Access('Committee','Docs') || $dir['Who'] == $USERID || $sub['Who'] == $USERID )) {
        $NewDir = $_POST{'NewDir'};
        $name = $dir['SN'];
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
          echo '<form action="Dir" method="post">';
          echo fm_hidden('d', $d);
        echo "<h2>Change Ownership of " . htmlspec($dir['SN']) . " to </h2>";
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
    case 'Restrict1': // Change access restrictions
      if ($d > 0 && (Access('Committee','Docs') || $dir['Who'] == $USERID || $sub['Who'] == $USERID )) {    
          echo '<form action="Dir" method="post">';
          echo fm_hidden('d', $d);
        echo "<h2>Restrict " . htmlspec($dir['SN']) . " to </h2>";
          $LocalAcc = array_slice($Access_Levels,0,$USER['AccessLevel']+1);
          $LocalAcc[0] = "All";
          echo fm_radio("Main User Level",$LocalAcc,$dir,'AccessLevel','',0);
          echo "<p>OPTIONALLY further restrict to one or more sections:<p>\n";
          $csects = explode(",",$dir['AccessSections']);
          foreach ($csects as $sec) $dir["Section_$sec"] = 1;
          foreach ($Sections as $Sect) echo " " . fm_checkbox($Sect,$dir,"Section_$Sect");
          echo '<br><input type="submit" value="Restrict" name="Action">';
          echo "</form>\n";
        $skip = 1;
      } else {
        $ErrMess = "Insufficient Priviledge";
      }
      break;
    case 'Restrict': // Change access restrictions
      if ($d > 0 && (Access('Committee','Docs') || $dir['Who'] == $USERID || $sub['Who'] == $USERID )) {
        $dir['AccessLevel'] = $_POST['AccessLevel'];
        $sects = [];
        foreach ($Sections as $Sect) if (isset($_POST["Section_$Sect"]) && $_POST["Section_$Sect"]) $sects[] = $Sect;
        $dir['AccessSections'] = ($sects ? implode(",",$sects) : ''); 
        Put_DirInfo($dir);                 
      } else {
        $ErrMess = "Insufficient Priviledge";
      }
      break;
    
    
    default:
  }

//if ($DBG) fwrite($DBG,"Got here\n");

  if (isset($_REQUEST{'FileAction'})) { $Act = $_REQUEST{'FileAction'}; }
  else { $Act = ''; }
  
  if (!$skip && $Act) {
    if (isset($_REQUEST{'f'})) {
      $f = $_REQUEST{'f'};
    } else {
      $f = 0;
    };
    $finf = Get_DocInfo($f);

//echo var_dump($finf) . "<p>";

    switch ($Act) {
      case 'Rename1':
        if (Access('Committee','Docs') || $finf['Who'] == $USERID || $dir['Who'] == $USERID ) {
            echo '<form action="Dir" method="post">';
            echo fm_hidden('d', $d) . fm_hidden('f', $f);
          echo "<h2>Rename " . htmlspec($finf['SN']) . "</h2>";
            echo fm_simpletext('as new name ',$_POST,'DocName');
            echo '<input type="submit" value="Rename" name="FileAction">';
            echo "</form>\n";
          $skip = 1;
        } else {
          $ErrMess = "Insufficient Priviledge";
        }
        break;
      case 'Rename':
        if (Access('Committee','Docs') || $finf['Who'] == $USERID || $dir['Who'] == $USERID ) {
          $NewDoc = $_POST{'DocName'};
          if ($finf['SN'] == $NewDoc) break;
          $fullname = File_FullName($f);
            $fullpath = dirname($fullname);
          if (file_exists("Store" . $fullpath . "/" . $NewDoc)) {
            $ErrMess = "Document already exists";
               break;
          }
          rename("Store" . $fullname, "Store" . $fullpath . "/" . $NewDoc);
           $finf['SN'] = addslashes($NewDoc);
          Put_DocInfo($finf);
        } else {
          $ErrMess = "Insufficient Priviledge";
        }
        break;
      case 'Move1':
        if (Access('Committee','Docs') || $finf['Who'] == $USERID || $dir['Who'] == $USERID ) {
            echo '<form action="Dir" method="post">';
            echo fm_hidden('d', $d) . fm_hidden('f', $f);
          echo "<h2>Move " . htmlspec($finf['SN']) . " to </h2>";
            echo Dir_All_Tree('NewDir',$finf['Dir']);
            echo '<input type="submit" value="Move" name="FileAction">';
            echo "</form>\n";
          $skip = 1;
        } else {
          $ErrMess = "Insufficient Priviledge";
        }
        break;
      case 'Move':
        if (Access('Committee','Docs') || $finf['Who'] == $USERID || $dir['Who'] == $USERID ) {
          $NewDir = $_POST{'NewDir'};
          $name = $finf['SN'];
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
            echo '<form action="Dir" method="post">';
            echo fm_hidden('d', $d) . fm_hidden('f', $f);
          echo "<h2>Change Ownership of " . htmlspec($finf['SN']) . " to </h2>";
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
        if (Access('Committee','Docs') || $finf['Who'] == $USERID || $dir['Who'] == $USERID ) {
          DeleteFile($f);
        } else {
          $ErrMess = "Insufficient Priviledge";
        }
        break;
      case 'Upload Document(s)':
      case 'Upload':
//if ($DBG) fwrite($DBG,"And Got here\n");
        $target_dir = "Store" . Dir_FullPname($d);
        // Count # of uploaded files in array
        $total = count($_FILES['uploads']['name']);
//if ($DBG) fwrite($DBG,"total = $total\n");
        // Loop through each file
        for($i=0; $i<$total; $i++) {
          //Get the temp file path
          $tmpFilePath = $_FILES['uploads']['tmp_name'][$i];
//if ($DBG) fwrite($DBG,"tmppath = $tmpFilePath\n");
          //Make sure we have a filepath
          if ($tmpFilePath != ""){
            //Setup our new file path
            $file = basename($_FILES['uploads']['name'][$i]);
            $target_file = $target_dir . "/" . $file;
//var_dump($tmpFilePath,$target_file);
            //Upload the file into the temp dir
            if(move_uploaded_file($tmpFilePath, $target_file)) {
               //Handle other code here
//if ($DBG) fwrite($DBG,"move worked\n");
              Doc_Create($file,$d,filesize($target_file));
            } else {
//if ($DBG) fwrite($DBG,"move failed\n");
              $ErrMess = "File failed to move to the Store";
            }
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
    echo "<button class='floatright FullD' onclick=\"($('.FullD').toggle())\">More Info</button><button class='floatright FullD' hidden onclick=\"($('.FullD').toggle())\">Less Info</button> ";
    echo "<h2>Directory: " . Get_Parent($d) . "</h2>";

    Doc_Table_Head();
 
 // Parent
 
    if ($d > 0) {
      $pid = $dir['Parent'];
      $pdir = Get_DirInfo($pid);
      
      List_dir_ent($pdir,'Parent');
    }

// Self
    if ($d) {
      List_dir_ent($dir,'Self',' class=FullD hidden');
    }


    if ($subs) {
      foreach($subs as $sub) {
        List_dir_ent($sub,'Directory');
      }
    }

    foreach($list as $file) Doc_List($file);
    
    echo "</tbody></table></div><p>\n";

//    fm_DragNDrop(0,1,'','- Do not upload more than 15M at once, for large files contact <a href=mailto:Richard@wavwebs.com>Richard</a>');
    
    echo "<form method=post action=Dir enctype='multipart/form-data' class=dropzone id=DirUpload >";
    echo fm_hidden('d', $d);
    echo fm_hidden('FileAction', 'Upload');
    echo "</form><script>";
    echo <<<XXX
    Dropzone.options.DirUpload = { 
      paramName: "uploads[]",
      init: function() {
        this.on("success", function(e,r) { document.open(); document.write(r); document.close(); });
      },
    };
XXX;
    echo "</script><p>";

/*
    echo '<form action="Dir" method="post" enctype="multipart/form-data" id=Uploads>';
    echo "Select file(s) to upload:";
    echo fm_hidden('d', $d);
    echo fm_hidden('FileAction', 'Upload');
    echo '<input type="file" name="uploads[]" multiple onchange=this.form.submit()>'; */
//    echo " &nbsp; &nbsp; Do not upload more than 15M at once, for large files contact <a href=mailto:Richard@wavwebs.com>Richard</a>.\n";
//    echo "</form>\n";
    echo '<form action="Dir" method="post">';
    echo fm_hidden('d', $d);
    echo fm_simpletext('New Directory',$_POST,'DirName');
    echo '<input type="submit" value="Create" name="Action">';
    echo "</form>\n";
    SearchForm($d);
  }

  dotail();
?>

