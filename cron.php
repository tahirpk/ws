
        <?php
        
        $server="localhost";
        $database="ws_portal";
        $user="root";
        $password="123";

        $conn=mysql_connect($server,$user,$password);
        $db_select=mysql_select_db($database,$conn);
        
           $htmlStyle = '<style type="text/css">
		.brd {
		border: 1px solid #ffff;
		background-color:#fffcfb;
		padding-left:10px;
		}
		table {
		margin-left: 0px;
		margin-top: 0px;
		margin-right: 0px;
		margin-bottom: 0px;
		background-color: #ffffff;
		}
                .tr_head {
		
		background-color:#83AE63;
		color: #FFFFFF;
		}
                .tr_sbhead {
		
		background-color:#B7C86B;
		color: #FFFFFF;
		}
		.pagging{
		color:#2163b0;
		font-family:verdana;
		font-size:13px;
		font-style:normal;
		font-weight:normal;
		text-decoration:none;
		}
		.pagging:hover{
		color:#555555;
		font-family:verdana;
		font-size:13px;
		font-style:normal;
		font-weight:normal;
		text-decoration:none;
		}
		.blacktext {
		font-family: Verdana, Arial, Helvetica, sans-serif;
		font-size: 14px;
		font-style: normal;
		font-weight: normal;
		color: #555555;
		text-decoration: none;
		}
		</style>';
        //$sql='select wb.id,wb.url,webId,pg.pageUrl,pageTitle,pageKeywords,pageContent,reportCheckStatus,reportStatus 
          //  from websites as wb, pages as pg where wb.id=pg.webId ';
        $sqlurl='select id,url from websites';
        $sqlurl=mysql_query($sqlurl) or die(mysql_error());
        $num_rows = mysql_num_rows($sqlurl);
        $table=$htmlStyle.'<table class="brd" width="100%" border="0" cellspacing="0" cellpadding="0">';
        while ($row = mysql_fetch_array($sqlurl)) {
           $sqlweb='select webId,pg.pageUrl,pageTitle,pageKeywords,pageContent,reportCheckStatus,reportStatus 
           from pages as pg where webId='.$row['id'];
        $table.='<tr class="tr_head "><td>Main Url:</td><td colspan="4">'. $row["url"].'</td></tr>';
        $table.='<tr class="tr_sbhead "><td>Page Url</td><td>Page Title</td><td>Keywords</td><td>Descriptions</td><td>Status</td></tr>';            
           $sqlweb= mysql_query($sqlweb);
           $num_prows = mysql_num_rows($sqlweb);
           if($num_prows>0)
           {
                while($prow=  mysql_fetch_array($sqlweb))
                {
                    if($row['id']==$prow['webId'])
                    {
                        if($prow["reportStatus"]==0)
                            $rpstatus='None';
                        if($prow["reportStatus"]==1)
                            $rpstatus='Only Title';
                        if($prow["reportStatus"]==2)
                            $rpstatus='Title and Keywords';
                        if($prow["reportStatus"]==3)
                            $rpstatus='Title,Keywords and Descriptions';
                        $table.='<tr><td>'.$prow["pageUrl"] .'</td><td>'.$prow["pageTitle"] .'</td><td>'.$prow["pageKeywords"] .'</td><td>'.$prow["pageContent"].'</td><td>'.$rpstatus.'</td></tr>';

                    }
                
                 }
           }
           else
           {
              
               $table.='<tr><td colspan="6" align="center">No page</td></tr>';
           }
    
            
   
        }
       $table.='</table>';
       
      // echo $table;
       
        $to		  = 'tahirpk@gmail.com,chris.adler@gmail.com';
        $from         = 'tahirpk@gmail.com';
        $subject      = 'Valid URLS Metatags.';
        //$Mail_header  = "Content-type: text/html\n";
        $Mail_header  = "MIME-Version: 1.0\r\n";
        $Mail_header .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $Mail_header .= "From: <$from>\r\n";
        @mail($to, $subject, $table, $Mail_header);
  ?> 