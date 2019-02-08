<?php
//$write_mail = $wr[0]." ".$wr[1]."  ".$crlf.$wr[2]." ".$wr[3]."  ".$crlf.$wr[4]." ".$wr[5]."  ";


$write_mail = "




<!doctype html>
<html>
    <head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Change Password</title>

    <!-- CSS Reset -->
    <style type='text/css'>
/* What it does: Remove spaces around the email design added by some email clients. */
      /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
html,  body {
	margin: 0 !important;
	padding: 0 !important;
	height: 100% !important;
	width: 100% !important;
}
/* What it does: Stops email clients resizing small text. */
* {
	-ms-text-size-adjust: 100%;
	-webkit-text-size-adjust: 100%;
}
/* What it does: Forces Outlook.com to display emails full width. */
.ExternalClass {
	width: 100%;
}
/* What is does: Centers email on Android 4.4 */
div[style*='margin: 16px 0'] {
	margin: 0 !important;
}
/* What it does: Stops Outlook from adding extra spacing to tables. */
table,  td {
	mso-table-lspace: 0pt !important;
	mso-table-rspace: 0pt !important;
}
/* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
table {
	border-spacing: 0 !important;
	border-collapse: collapse !important;
	table-layout: fixed !important;
	margin: 0 auto !important;
}
table table table {
	table-layout: auto;
}
/* What it does: Uses a better rendering method when resizing images in IE. */
img {
	-ms-interpolation-mode: bicubic;
}
/* What it does: Overrides styles added when Yahoo's auto-senses a link. */
.yshortcuts a {
	border-bottom: none !important;
}
/* What it does: Another work-around for iOS meddling in triggered links. */
a[x-apple-data-detectors] {
	color: inherit !important;
}
</style>

    <!-- Progressive Enhancements -->
    <style type='text/css'>
        
        /* What it does: Hover styles for buttons */
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
        .button-td:hover,
        .button-a:hover {
			color: #ffffff !important;
            background: #555555 !important;
            border-color: #555555 !important;
        }

        

        /* Media Queries */
        @media screen and (max-width: 600px) {

            .email-container {
                width: 320px !important;
            }

            /* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */
            .fluid,
            .fluid-centered {
                max-width: 100% !important;
                height: auto !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }
            /* And center justify these ones. */
            .fluid-centered {
                margin-left: auto !important;
                margin-right: auto !important;
            }

            /* What it does: Forces table cells into full-width rows. */
            .stack-column,
            .stack-column-center {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
            }
            /* And center justify these ones. */
            .stack-column-center {
                text-align: center !important;
            }
        
            /* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */
            .center-on-narrow {
                text-align: center !important;
                display: block !important;
                margin-left: auto !important;
                margin-right: auto !important;
                float: none !important;
            }
            table.center-on-narrow {
                display: inline-block !important;
            }
                
        }

    </style>
    </head>
    <body bgcolor='#EEEEEE' width='100%' style='margin: 0;' yahoo='yahoo'>
    <table bgcolor='#EEEEEE' cellpadding='0' cellspacing='0' border='0' height='100%' width='100%' style='border-collapse:collapse;'>
      <tr>
        <td><center style='width: 100%;'>
            <br><br><br>
             
			
			
			
		<!-- Email Spase : BEGIN -->
            <table cellspacing='0' cellpadding='0' height='100' border='0' align='center'>
                    <tr>
					<td style=' padding: 40px; text-align: center; font-family: sans-serif; font-size: 38px; mso-height-rule: exactly; line-height: 41px; color: #555555;'><strong>Change Password</strong></td>
					</tr>
            </table>
          <!-- Email Spase : END -->
			
			
			
            
            <!-- Email Body : BEGIN -->
            <table style='border-radius: 3px; width:500px;' cellspacing='0' cellpadding='0' border='0' align='center' bgcolor='#ffffff' width='100' class='email-container'>
            
             
            
            <!-- 1 Column Text : BEGIN -->
            <tr>
                <td style='border-radius: 3px; padding: 40px; text-align: center; font-family: sans-serif; font-size: 18px; mso-height-rule: exactly; line-height: 20px; color: #555555;'> 
				
				".$wr[3]."
				<br><br>
				".$wr[5]."
                <br>
                
              	</td>
            </tr>
            <!-- 1 Column Text : END --> 
      	    </table>
            <!-- Email Body : END --> 
            
			
			
			
			
			<!-- Email Spase : BEGIN -->
            <table cellspacing='0' cellpadding='0' height='50' border='0' align='center'>
                    <tr>
					<td></td>
					</tr>
            </table>
          <!-- Email Spase : END -->
			
			
			
			
            <!-- Email Footer : BEGIN -->
            <table cellspacing='0' cellpadding='0' width='150' border='0' align='center' style='margin: auto'>
                    <tr>
						
                    <td style=' border-radius: 3px; background: #ffffff; text-align: center;' class='button-td'>
						
					<a href='https://thepobelka.ru/components/Admin/authentication.php' style='background: #ffffff; border: 15px solid #ffffff; padding: 0 10px;color: #555555; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;' class='button-a'> 
                      <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->Get<!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]--> 
                    </a>
						
					</td>
						
                  	</tr>
            </table>
          <!-- Email Footer : END -->
			<br><br><br>
					
			
            
          </center></td>
      </tr>
    </table>
</body>
</html>





";