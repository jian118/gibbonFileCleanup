<?php
/*
 * setup basic data to allow reports to be written
 */


//Set the module URL in module.js
echo "<script type='text/javascript'>ajaxUrl = '" . $_SESSION[$guid]['absoluteURL'] . "/modules/" . $_SESSION[$guid]['module'] . "/z-fileclean-ajax.php';</script>";

if (isActionAccessible($guid, $connection2,"/modules/File Cleanup/z-fileclean.php")==FALSE) {
    //Acess denied
    print "<div class='error'>" ;
    print "You do not have access to this action." ;
    print "</div>" ;
} else {
    echo "<h3>File List</h3>";
    if(isset($_GET['path'])){
        if(strpos($_GET['path'],"..")!== false) {
            print "<div class='error'>";
            print "Something went wrong. Please contact system admin.";
            print "</div>";
            exit();
        }
        echo "<button class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded' onclick='backToList()'>Back to Folder List</button>&emsp;";
        echo "<button class='actionBtn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded' onclick='hideNA()'>Show/Hide File Paths</button>&emsp;";
	echo "<button class='actionBtn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded' onclick='cpNA()'>Copy File Paths to Clipboard</button>&emsp;";
	echo "<br/><br/>";
        echo "<span class='loading lds-dual-ring'></span>";
        
        arrayFilter(dirToArray("uploads/".$_GET['path']),$connection2);
    }else{
        ?>
        <p class="text-red-700 font-bold">Warning: Please backup your uploads folder and MySQL database before using this tool.</p>
        <ul class="list-decimal">
            <li>Select the year and month in the list below to list files uploaded during that period of time</li>
            <li>Wait for the list to process. Some folders may take longer depending on the number of files.</li>
            <li>Copy the orphaned files to your computer's clipboard. This copies the absolute path fo the files as a list.<br></li>
            <li>Go to phpmyadmin -> select your database -> search -> paste into search -> Select all tables. -> Go<br>
                (Consider dividing the list in your favourite text editor to process as a longer list would take a much longer time)</li>
            <li>If search returns with result, you may add the table and column into the z-fileclean-ajax.php( under function searchDB )</li>
            <li>Repeat again until there's no result in the search</li>
            <li>Once you are sure that the all of the orphaned files are not attributed to any data, you may delete them. Depending on your operating system, use the below to delete the files:<br/>
                <ul>
                    <li>
                        <b>Linux/MacOS:</b>
                        <pre>
                            <?php echo "cd " . getcwd(); ?><br/>
                            rm uploads\2017\04\1.docx uploads\2017\04\2.docx
                        </pre>
                    </li>
                    <li>
                        <b>Windows</b>
                        <br/>
                        <pre>
                            <?php echo "cd " . getcwd(); ?><br/>
                            del /Q uploads\2017\04\1.docx uploads\2017\04\2.docx
                        </pre> 
                    </li>
                </ul>
            </li>
        </ul><br>
        <?php
        listDirectory(dirToArray('uploads',2));
    }


}

//Using fileIsValid, return an array with only the valid files
function validObjectsFromArray($fileArr)
{
    $resultArr = [];
    foreach($fileArr as $file)
    {
        if(fileIsValid($file))
        {
           array_push($resultArr,$file); 
        }
    }
    return $resultArr;
}

//Returns true if the file is not one of the protected file names
function fileIsValid($value)
{
    return !in_array($value,array(".","..",".htaccess","cache","web.config"));
}

function dirToArray($dir,$level=2) {

    $result = array();
    $cdir = validObjectsFromArray(scandir($dir));
    foreach ($cdir as $key => $value)
    {
        if (fileIsValid($value))
        {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value) && $level>0)
            {
                //Value is a directory, throw it's contents into the result array
                $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value,$level-1);
            }
            else
            {
                //Value is a file
                $result[] = $value;
            }
        }
    }
    return $result;
}

function listDirectory($arr){
    $yearsShown = 0;
    if(is_array($arr) && sizeof($arr) > 0)
    {
        echo "<style>ul.listd>li>ul>li{display:inline-block;width:50px;}</style>";
        echo "<ul class='listd'>";
        foreach($arr as $ykey=>$year) {
            if(is_array($year))
            {
                echo "<li>$ykey<ul>";
                foreach ($year as $mkey => $monthContents) {
                    $fp = $ykey . "/" . $mkey;
                    echo "<li><button class='rounded py-2 px-4' onclick='window.location+=\"&path=$fp\"'>$mkey</button></li>";

                }
                echo "</ul></li>";
                $yearsShown++;
            }
        }
        echo "</ul>";
    }
    if($yearsShown == 0) //Sometimes empty folders may exist in the uploads directory, hence the int
    {
        echo "No years/months found with orphaned files!";
    }
}

function arrayFilter($arr,$dbh){
    echo "<table style='margin:10px;'>";
    foreach($arr as $key=>$file){
        $fp = "uploads/" . $_GET["path"] . "/" . $file;
        echo "<tr>";
        echo "<td>";
        echo $fp;
        echo "</td>";
        echo "<td class='chkFile'>";
        echo "Loading";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}


?>
