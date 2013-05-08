<?php
  switch($_GET["file"]){
      case "jquery.cropzoom.js":
            $log = "jquery.cropzoom.log";
            $file = "js/jquery.cropzoom.js";
            break;
      case "jquery.cropzoom.min.js":
            $log = "jquery.cropzoom.min.log"; 
            $file = "js/jquery.cropzoom.min.js"; 
            break;
      case "jquery.cropzoom.pack.js":
            $log = "jquery.cropzoom.pack.log"; 
            $file = "js/jquery.cropzoom.pack.js"; 
            break;
      case "jquery.cropzoom.css":
            $log = "jquery.cropzoom.css.log"; 
            $file = "css/jquery.cropzoom.css"; 
            break;
      case "image_movement":
            $log = "jquery.cropzoom.image.log"; 
            $file = "images/movement.png"; 
            break;
      case "resize_and_crop.zip":
            $log = "resize_and_crop.log";
            $file = "resize_and_crop.zip";  
            break;
      case "cropzoom_in_net.zip":
            $log = "cropzoom_in_net.log";
            $file = "CropZoom_in_Net.zip";  
            break;
  }

  $actualSize = @file_get_contents($log);
  
  $fp = fopen($log,"w");
  fwrite($fp,$actualSize + 1);
  fclose($fp);
    
  header("Pragma: public"); // required
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false); // required for certain browsers 
  header("Content-Type: application/force-download");
  // change, added quotes to allow spaces in filenames, by Rajkumar Singh
  header("Content-Disposition: attachment; filename=\"".basename($file)."\";" );
  header("Content-Transfer-Encoding: binary");
  header("Content-Length: ".filesize($file));
  readfile("$file");
    exit();
  readfile($file);
?>
