<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

// Gibbon system-wide include
if($_SERVER['HTTP_REFERER'] == null)
{
    //This request has been done directly, immediately exit
    echo "Direct AJAX calls not allowed";
    exit(401);
}

require_once '../../gibbon.php';

if (isActionAccessible($guid, $connection2, '/modules/File Cleanup/z-fileclean.php') == false) {
    // Access denied
    die(__('Your request failed because you do not have access to this action.') );
} else {
    if(($_POST['chkFile']) && strpos($_POST['chkFile'],"uploads")===0){
        searchDB($_POST['chkFile'],$connection2);
    }
}

function searchDB($criteria,$dbh){
    $chkLoc = array(
        //table => column
        'gibbonUnit' => ['details'],
        'gibbonUnitBlock' => ['contents','teachersNotes'],
        'gibbonUnitClassBlock' => ['contents','teachersNotes'],
        'gibbonResource' => ['content'],
        'gibbonPerson' => ['image_240','birthCertificateScan','	citizenship1PassportScan','nationalIDCardScan'],
        'gibbonMarkbookEntry' => ['response'],
        'gibbonMarkbookColumn' => ['attachment'],
        'gibbonMessenger' => ['body'],
        'policiesPolicy' => ['location'],
        'gibbonPlannerEntry' => ['teachersNotes']
    );
    $use = 0;
    foreach($chkLoc as $table=>$cols) {
        foreach($cols as $col){
            try {
                $data = array(
                    'criteria' => "%" . $criteria . "%"
                );
                $sql = "SELECT * FROM $table WHERE $col LIKE :criteria";
                $rs = $dbh->prepare($sql);
                $rs->execute($data);
                $resCount = $rs->rowCount();
                if ($resCount > 0) {
                    echo $resCount . " in $table";
                    $use =1;
                    break(2); // stop searching
                }
            } catch (PDOException $e) {
                //print "<div>" . $e->getMessage() . "</div>";
            }
        }
    }
    if(!$use){
        echo "NA";
    }
}
