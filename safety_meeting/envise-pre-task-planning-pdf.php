<?php
ob_start();
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
//echo dirname(dirname(dirname(__FILE__))).'/html2pdf_v4.03/html2pdf.class.php';exit;
include_once dirname(dirname(__FILE__)).'/_inc.php';
# Get client details
$query = "SELECT * FROM client WHERE id = '$client'";
$result_client = mysql_query($query);
$ob_client = mysql_fetch_array($result_client);

$checked = '<img   src="../../img/checked.png">';
$unchecked = '<img   src="../../img/unchecked.png">';
$checked_box = '<img   src="../../img/checked_checkbox.png">';
$unchecked_box = '<img   src="../../img/unchecked_checkbox.png">';
?>
<page style="font-size: 12px">
<table style="*border: solid 1px #440000; width: 100%"    cellspacing="0"  align="center">

    <tr><!----1st row--->
        <td style="width: 100%">

            <table style="*border: solid 1px #440000; width: 100%"    cellspacing="0"  align="center">
                <tr>
                    <th style="width: 25%;padding: 5px;line-height: 1.42857143;">
                        <?php if ($ob_client['logo']) { ?>
                        <img id="applogo" style="float:left;"  src="../../logos/<?php echo $ob_client['logo']; ?>" width="100" >
                        <?php } else { ?>
                        <h1><?php echo $ob_client['name']; ?></h1>
                        <?php } ?>
                    </th>
                    <th style="width: 50%;padding: 5px;line-height: 1.42857143;">
                        <h3 style="vertical-align: middle;text-align:center;font-size: 20px;font-weight: bold;line-height: 1.1;">
                            ENVISE <br> EMPLOYEE PRE-TASK PLANNING <br> & SAFETY REPORT
                        </h3>
                    </th>
                     <th style="width: 25%;padding: 5px;line-height: 1.42857143;">
                        <img  style="float:right;" src="../../img/NCI-Logo.png" width="100" >
                    </th>
                </tr>
            </table>

        </td>
    </tr><!----1st row--->
    <tr><!----2nd row-->
        <td style="width: 100%;">

            <!----1st table----->
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr style="padding:5px; line-height:1.42857143;">
                    <td style="width:15%;padding:5px; line-height:1.42857143;">
                        EMPLOYEE :
                    </td>
                    <td style="width:33%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                    <td style="width:3%;padding:5px; line-height:1.42857143;">&nbsp;</td>
                    <td style="width:20%;padding:5px; line-height:1.42857143;">
                        FOREMAN/SUPERVISOR:
                    </td>
                    <td style="width:29%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                </tr>
                <tr><td colspan="5" style="width:100%;">&nbsp;</td></tr>
            </table>
            <!----2nd table----->
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr style="padding:5px; line-height:1.42857143;">
                    <td style="width:15%;padding:5px; line-height:1.42857143;">
                        PROJECT(S):
                    </td>
                    <td style="width:33%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                    <td style="width:3%;padding:5px; line-height:1.42857143;">&nbsp;</td>
                    <td style="width:20%;padding:5px; line-height:1.42857143;">
                        MONTH:
                    </td>
                    <td style="width:20%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                    <td style="width:4%;padding:5px; line-height:1.42857143;"> 20,</td>
                    <td style="width:5%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                </tr>
                <tr><td colspan="7" style="width:100%;">&nbsp;</td></tr>
            </table>

        </td>
    </tr><!----2nd row--->
    <tr><!----3rd row-->
        <td style="width: 100%;">

            <!----1st table----->
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width: 100%;padding: 5px;line-height: 1.42857143;">
                        <div style="vertical-align: middle;text-align:center;font-size: 16px;line-height: 1.1;">
                           <b><u>TAKE TWO</u></b> MINUTES AND PLAN FOR SAFETY
                        </div>
                    </td>
                </tr>
            </table>
            <!----2nd table----->
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr style="padding:5px; line-height:1.42857143;">
                    <td style="width:15%;padding:5px; line-height:1.42857143;">
                        Project :
                    </td>
                    <td style="width:33%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                    <td style="width:3%;padding:5px; line-height:1.42857143;">&nbsp;</td>
                    <td style="width:20%;padding:5px; line-height:1.42857143;">
                        Trade:
                    </td>
                    <td style="width:29%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                </tr>
                <tr><td colspan="5" style="width:100%;">&nbsp;</td></tr>
                <tr style="padding:5px; line-height:1.42857143;">
                    <td style="width:15%;padding:5px; line-height:1.42857143;">
                        Date :
                    </td>
                    <td style="width:33%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                    <td style="width:3%;padding:5px; line-height:1.42857143;">&nbsp;</td>
                    <td style="width:20%;padding:5px; line-height:1.42857143;">
                        Foreman:
                    </td>
                    <td style="width:29%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                </tr>
                <tr><td colspan="5" style="width:100%;">&nbsp;</td></tr>
            </table>
            <!----3rd table----->
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr style="padding:5px; line-height:1.42857143;">
                    <td style="width:100%;padding:2px; line-height:1.42857143;text-align: center;height: 10px;background-color: #0E76BC;color: #fff;padding-top: 11px;border: 2px solid #0E76BC;border-radius: 7px 7px 0 0;border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;">
                        Plan out your work before you start and throughout your day.
                    </td>
                </tr>
                <tr style="padding:5px; line-height:1.42857143;">
                    <td style="width:100%;text-align: center;padding: 8px 0;color: #fff;background-color: #000;border: 2px solid #000;">
                        SAFETY PROCEDURES(Check all that apply)
                    </td>
                </tr>
            </table>
            <!----2nd table----->
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Layout/ Tremble
                    </td>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Install Anchors
                    </td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Install Straps/Hangers
                    </td>
                </tr>
                <tr>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Install Pipe
                    </td>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Install Duct
                    </td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Welding/Brazing/Soldering
                    </td>
                </tr>
                <tr>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Cutting
                    </td>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Drilling/Coring
                    </td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Process Fittings
                    </td>
                </tr>
                <tr>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Material Handling
                    </td>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Fabrication/Assembly
                    </td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Installing Seismic
                    </td>
                </tr>
                <tr>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Crane Pick
                    </td>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Housekeeping
                    </td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Demolition
                    </td>
                </tr>
                <tr>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Installing mechanical equipment
                    </td>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Installing fixtures
                    </td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Other
                    </td>
                </tr>
                <tr>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Maintance Inspection
                    </td>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">&nbsp;</td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">&nbsp;</td>
                </tr>
                <tr><td colspan="3" style="width:100%;">&nbsp;</td></tr>
            </table>

        </td>
    </tr><!----3rd row--->
    <tr><!----4th row-->
        <td style="width: 100%;">
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:35%;text-align: center;padding: 8px 0;color: #fff;background-color: #000;border: 2px solid #000;">
                        What additional tasks are you performing?
                    </td>
                    <td style="width:65%;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" style="width:100%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                </tr>
                <tr><td colspan="2" style="width:100%;">&nbsp;</td></tr>
            </table>
        </td>
    </tr><!----4th row--->
    <tr><!----5th row-->
        <td style="width: 100%;">

            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr style="padding:5px; line-height:1.42857143;">
                    <td style="width:100%;text-align: center;padding: 8px 0;color: #fff;background-color: #000;border: 2px solid #000;">
                        SAFETY PROCEDURES(Check all that apply)
                    </td>
                </tr>
            </table>
            <!----2nd table----->
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Unprotected Fall Hazards
                    </td>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Scaffolds
                    </td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Powder actuated/HILTI Tools
                    </td>
                </tr>
                <tr>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Rooftop
                    </td>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Scissor/Boom lift
                    </td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Forklift
                    </td>
                </tr>
            </table>
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Chemicals
                    </td>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Welding/cutting
                    </td>
                    <td style="width:14%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Other
                    </td>
                    <td style="width:20%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                </tr>
            </table>
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Rigging/Rope
                    </td>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Ladders A-Frame/Extension
                    </td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Power Tools
                    </td>
                </tr>
                <tr><td colspan="3" style="width:100%;">&nbsp;</td></tr>
            </table>

        </td>
    </tr><!----5th row--->
    <tr><!----6th row-->
        <td style="width: 100%;">
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:35%;text-align: center;padding: 8px 0;color: #fff;background-color: #000;border: 2px solid #000;">
                        What additional hazards are present?
                    </td>
                    <td style="width:65%;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" style="width:100%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                </tr>
                <tr><td colspan="2" style="width:100%;">&nbsp;</td></tr>
            </table>
        </td>
    </tr><!----6th row--->
    <tr><!----7th row-->
        <td style="width: 100%;">

            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr style="padding:5px; line-height:1.42857143;">
                    <td style="width:100%;text-align: center;padding: 8px 0;color: #fff;background-color: #000;border: 2px solid #000;">
                        SAFETY PRECAUTIONS(Check all that apply)
                    </td>
                </tr>
            </table>
            <!----2nd table----->
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:26%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Goggles/Fasesheild
                    </td>
                    <td style="width:40%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Harness/Lanyard
                    </td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Disposable Respirator
                    </td>
                </tr>
                <tr>
                    <td style="width:26%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Fire Extinguisher
                    </td>
                    <td style="width:40%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Task Lighting
                    </td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Shade/Water/Sunscreen(Heat Illness)
                    </td>
                </tr>
                <tr>
                    <td style="width:26%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Ventilation
                    </td>
                    <td style="width:40%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Fire-Resistant & Arc flash Clothes/Gloves
                    </td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Lockout/Tagout
                    </td>
                </tr>
            </table>
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:26%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;Hole Covers
                    </td>
                    <td style="width:40%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Material Handling Aids
                    </td>
                    <td style="width:14%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Other
                    </td>
                    <td style="width:20%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                </tr>
                <tr><td colspan="4" style="width:100%;">&nbsp;</td></tr>
            </table>

        </td>
    </tr><!----7th row--->
    <tr><!----8th row-->
        <td style="width: 100%;">
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:35%;text-align: center;padding: 8px 0;color: #fff;background-color: #000;border: 2px solid #000;">
                        What additional precautions must be taken?
                    </td>
                    <td style="width:65%;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" style="width:100%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                </tr>
                <tr><td colspan="2" style="width:100%;">&nbsp;</td></tr>
            </table>
        </td>
    </tr><!----8th row--->
    <tr><!---- row-->
        <td style="width: 100%;">

            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:86%;text-align: center;padding: 7px 0px;color: #fff;background-color: #000;border: 2px solid #000;">
                        SAFETY CHECKLIST<br>
                        (all boxes must be checked. If the answer is YES to any questions, contact your Foreman/Supervisor before proceeding)
                    </td>
                    <td style="width:7%;text-align: center;padding: 0px 0px;color: #fff;background-color: #000;border: 2px solid #000;">
                        YES
                    </td>
                    <td style="width:7%;text-align: center;padding: 0px 0px;color: #fff;background-color: #000;border: 2px solid #000;">
                        NO
                    </td>
                </tr>
            </table>
            <!----2nd table----->
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Attend daily two minute start up meeting
                    </td>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                       Do todays tasks require special training/certification?
                    </td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                </tr>
                <tr>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Bring any Safety Issues to your Foreman immediately
                    </td>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                       Do todays tasks require special tools or equipment?
                    </td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                </tr>
                <tr>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Don't take chances if in doubt ask your Forman
                    </td>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                       Do todays tasks require review of Safety Data Sheets?
                    </td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                </tr>
                <tr>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Insure that your work are is free of hazards
                    </td>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                       Will weather be a safety concern today?
                    </td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                </tr>
                <tr>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Wear all required Personal Protective Equipment
                    </td>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                       Are barricades warning tape or safety signs required?
                    </td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                </tr>
                <tr>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Inspect all tools and equipment before use.
                    </td>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                       Is the weight of the materials your handling more than 50lbs?
                    </td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                </tr>
                <tr>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Be trained with all tools before use.
                    </td>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                       Are there any new crew members that require support?
                    </td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                </tr>
                <tr>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       There are enough personal to complete the task
                    </td>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                       Are you working in trenches or confined spaces?
                    </td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                </tr>
                <tr>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Has a pre task plan been completed for high risk work
                    </td>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                       Are ladders visually inspected prior to use?
                    </td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; <?php echo $unchecked; ?> &nbsp;</td>
                </tr>
                <tr>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Do you know who to contact in an emergency
                    </td>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">&nbsp; </td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; </td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Are ladders visually inspected prior to use
                    </td>
                    <td style="width:43%;padding:5px; line-height:1.42857143;">&nbsp; </td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp; </td>
                    <td style="width:7%;text-align: center;padding:5px; line-height:1.42857143;">&nbsp;</td>
                </tr>
                <tr><td colspan="4" style="width:100%;">&nbsp;</td></tr>
            </table>
        </td>
    </tr><!---- row--->

    <tr><!----9th row-->
        <td style="width: 100%;">

            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:100%;text-align: center;padding: 8px 0;color: #fff;background-color: #000;border: 2px solid #000;">
                        SAFE WORK PRACTICES(CHECK ALL THAT APPLY)
                    </td>
                </tr>
            </table>
            <!----2nd table----->
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:100%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Know the location of the closest emergency exit, fire extinguisher, and first aid kit to your work area.
                    </td>
                </tr>
                <tr>
                    <td style="width:100%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Make sure you have the correct size and type of ladder and that it is setup and being used properly
                    </td>
                </tr>
                <tr>
                    <td style="width:100%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Ensure that all power cords are inspected and free of damage, and laid out to minimize trip hazards.
                    </td>
                </tr>
                <tr>
                    <td style="width:100%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Use the right tool for the job. Use the tool the way the manufacture designed it to be used.
                    </td>
                </tr>
                <tr>
                    <td style="width:100%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Flammable and compressed gasses must be properly used, secured, transported and stored properly when not in use.
                    </td>
                </tr>
                <tr>
                    <td style="width:100%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Fall protection equipment must be inspected before each use and stored properly when not in use.
                    </td>
                </tr>
                <tr>
                    <td style="width:100%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Ensure your work area has proper lighting and ventilation. Stay clear of work causing dust, mists, or vapors.
                    </td>
                </tr>
                <tr>
                    <td style="width:100%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Do not run on the jobsite. Do not listen to music or use headphones. Cell phones may only be used on breaks.
                    </td>
                </tr>
                <tr>
                    <td style="width:100%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Smoking, including the use of e-cigarettes, is only permitted in approved areas.
                    </td>
                </tr>
                <tr>
                    <td style="width:100%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                       Clean up after you make a mess. Housekeeping should be conducted on an on-going basis.
                    </td>
                </tr>
                <tr><td style="width:100%;">&nbsp;</td></tr>
            </table>

        </td>
    </tr><!----9th row--->

    <tr><!----10th row-->
        <td style="width: 100%;">

            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:100%;text-align: center;padding: 8px 0;color: #fff;background-color: #000;border: 2px solid #000;">
                        EMPLOYEE SAFETY REPORT (Check all that apply)
                    </td>
                </tr>
            </table>
            <!----2nd table----->
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;I reported a Safety Hazard
                    </td>
                    <td style="width:33%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;I resolved a Safety Issue
                    </td>
                    <td style="width:34%;padding:5px; line-height:1.42857143;">
                       &nbsp; <?php echo $unchecked_box; ?> &nbsp;I had a Safety Suggestion
                    </td>
                </tr>
                <tr><td colspan="3" style="width:100%;">&nbsp;</td></tr>
            </table>
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:10%;padding:5px; line-height:1.42857143;">Description:</td>
                    <td style="width:90%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                </tr>
                <tr><td colspan="2" style="width:100%;">&nbsp;</td></tr>
            </table>
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:10%;padding:5px; line-height:1.42857143;">Reported to:</td>
                    <td style="width:20%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Supervisor
                    </td>
                    <td style="width:20%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Envise Safety
                    </td>
                    <td style="width:20%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;General
                    </td>
                    <td style="width:10%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Other
                    </td>
                    <td style="width:20%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                </tr>
                <tr><td colspan="6" style="width:100%;">&nbsp;</td></tr>
            </table>

        </td>
    </tr><!----9th row--->

    <tr><!----10th row-->
        <td style="width: 100%;">

            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:50%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;I was involved in an incident
                    </td>
                    <td style="width:50%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;I had a violation
                    </td>
                </tr>
                <tr>
                    <td style="width:50%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Accident
                    </td>
                    <td style="width:50%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Unsafe act
                    </td>
                </tr>
                <tr>
                    <td style="width:50%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Near-Miss
                    </td>
                    <td style="width:50%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Unsafe condition
                    </td>
                </tr>
                <tr>
                    <td style="width:50%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Injury
                    </td>
                    <td style="width:50%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Unsafe Procedure
                    </td>
                </tr>
                <tr>
                    <td style="width:50%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Other
                    </td>
                    <td style="width:50%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Improper PPE
                    </td>
                </tr>
                <tr>
                    <td style="width:50%;padding:5px; line-height:1.42857143;">&nbsp;</td>
                    <td style="width:50%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;Other
                    </td>
                </tr>
                <tr><td colspan="2" style="width:100%;">&nbsp;</td></tr>
            </table>

            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:10%;padding:5px; line-height:1.42857143;">Description:</td>
                    <td style="width:90%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">
                        &nbsp;
                    </td>
                </tr>
                <tr><td colspan="2" style="width:100%;">&nbsp;</td></tr>
            </table>

            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td colspan="3" style="width:100%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;
                        <b><u>I reported to work today fit for duty</u> (free of injury/illness, not under the influence of drugs or alcohol)</b>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%;padding:5px; line-height:1.42857143;">
                        &nbsp; <?php echo $unchecked_box; ?> &nbsp;<b><u>I did not get injured or become ill on the job today.</u></b>
                    </td>
                    <td style="width:10%;padding:5px; line-height:1.42857143;">
                        Initial:
                    </td>
                    <td style="width:40%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">

                    </td>
                </tr>
                <tr><td colspan="3" style="width:100%;">&nbsp;</td></tr>
            </table>
            <table style=" width: 100%;"    cellspacing="0"  align="center">
                <tr>
                    <td style="width:10%;padding:5px; line-height:1.42857143;">
                        Employee:
                    </td>
                    <td style="width:40%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">

                    </td>
                    <td style="width:10%;padding:5px; line-height:1.42857143;">
                        Signature:
                    </td>
                    <td style="width:40%;border-bottom: 1px solid #000;padding:5px; line-height:1.42857143;">

                    </td>
                </tr>
                <tr><td colspan="4" style="width:100%;">&nbsp;</td></tr>
            </table>

        </td>
    </tr><!----10th row--->


</table>
</page>
<?php

$content = ob_get_clean();

# convert to PDF
require_once (dirname(dirname(dirname(__FILE__))).'/html2pdf_v4.03/html2pdf.class.php');

try
{
    $html2pdf = new HTML2PDF('P', 'A4', 'en');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('envise-pre-task-planing.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
?>
