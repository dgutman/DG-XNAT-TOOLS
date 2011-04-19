<?php


$connect_params = " -host http://xnat.cci.psy.emory.edu:8080/xnat -u nbia -p nbia ";

$BASE_COMMAND = " /home/cci/XNATIFY/xnat_tools/XNATRestClient $connect_params ";

$current_project="DG_TESTING";

/*#####
### SO I am going to specify a subject ID... and then get the corresponding URI...
## I am then going to get the correspidning EXPERIMENT ID for htat aubject... so first things' first... 
*/
$SUBJECT_ID_TO_LOOK_FOR="TCGA_06-0130";


/*##LIST SUBJET EXPERIMENTS

###http://xnat.cci.psy.emory.edu:8080/xnat/REST/projects/DG_TESTING/subjects/TCGA_06-0130/experiments */

$GET_SUBJECT_STRING = "\"/REST/projects/$current_project/subjects/${SUBJECT_ID_TO_LOOK_FOR}/experiments?format=csv\"";

$FULL_SYNTAX = $BASE_COMMAND . " -m GET -remote " . $GET_SUBJECT_STRING;
echo $FULL_SYNTAX. "\n";
$FULL_SUBJECT_EXPERIMENT_LIST_INFO = `$FULL_SYNTAX`;
echo $FULL_SUBJECT_EXPERIMENT_LIST_INFO;

$return_data = array();

$return_data = str_getcsv($FULL_SUBJECT_EXPERIMENT_LIST_INFO);
for($k=0;$k<count($return_data);$k++)
	{


	echo "$k;" . $return_data[$k] ." \t";
	}
	echo "\n";

exit;
/*

###

### EXAMPLE COMMANDS:::
## THIS WILL GET ALL THE SUBJECTS FOR THE NBIA_TCGA PROJECT...
$REST_PART = "\"/REST/projects/$current_project/subjects?format=csv\"";
$FULL_SYNTAX = $BASE_COMMAND . " -m GET -remote " . $REST_PART;
@FULL_SUBJECT_LIST_INFO = `$FULL_SYNTAX`;
print @FULL_SUBJECT_LIST_INFO;
exit;
###  I NNOW HAVE AN ARRAY LISTING the csv output from the above command as a line


#print $FULL_SUBJECT_LIST_INFO;
for($i=1;$i<=$#FULL_SUBJECT_LIST_INFO;$i++)
	{
	$line = $FULL_SUBJECT_LIST_INFO[$i];
	chomp($line);
	$line =~ s/\"//g;

	($ID,$project,$label,$insert_date,$insert_user,$URI)  = split(/,/,$line);
	#print "$i;$ID,$label,$URI\n";
#	print $line . "\n";
	### NOW FOR EACH PATIENT/LABEL I WANT TO GET SOME MORE INFO... LIKE THE SCAN IDS or more importantly.. the LABELS for the individuals..
	
	##  THIS STATEMENT WILL GET ALL OF THE INIDIVUDAL SCAN SESSIONS FOR THAT PATIENT.. WOO HOO..
	$new_rest_statement = "\"/REST/projects/$current_project/subjects/$label/experiments?format=csv\"";
	$FULL_SYNTAX = $BASE_COMMAND . " -m GET -remote ". $new_rest_statement;

#	print $FULL_SYNTAX  ."\n";
	@EXPERIMENT_STATUS = `$FULL_SYNTAX`;
if($#EXPERIMENT_STATUS == 0 ) { print "We ain't found shit\n";}
elsif ($#EXPERIMENT_STATUS > 0 )	
			{
			#print "YEAH!!! THIS SUBJECT ACTUALLY HAS EXPERIMENTS... IVE NEVER BEEN MORE EXCITED!!!!! $label!!! \n";

			for($j=0;$j<=$#EXPERIMENT_STATUS;$j++)
				{
				$EXPERIMENT_STATUS[$j] =~ s/\"//g;
				$other_line = $EXPERIMENT_STATUS[$j];
				chomp($other_line);
				($subjectasseor,$ID_2,$project_2,$date,$type,$label_2, $ins_date, $URI ) = split(/,/,$other_line);
				print "$j;$ID_2;$label_2;$URI\n";



				}

			}

			$new_new_rest_statement = "\"/REST/projects/$current_project/subjects/$label/experiments/$label_2/scans?format=csv\"";
			$FULL_SYNTAX = $BASE_COMMAND . " -m GET -remote ". $new_new_rest_statement;
			@sessions = `$label_2`;
			print @sessions
		
#	system($FULL_SYNTAX);

#	exit;

	}

*/

?>
