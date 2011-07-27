<?php

// we need the names of an input file and two output files
if ( $argc < 3 )
	die( "Not all needed file names were provided." );

// the first argument is the name of the test results file to parse
// second argument is the file where we will output the information about the respondents
// third argument is the file where we will output their answers
$resultsFileName = $argv[1];
$respondentFileName = $argv[2];
$answerFileName = $argv[3];
$whereToBreak = $argv[4];

// open our files and check the handles
$resultsFile = fopen( $resultsFileName, "r" );
$respondentFile = fopen( $respondentFileName, "w" );
$answerFile = fopen( $answerFileName, "w" );

if ( !$resultsFile )
	die( "Could not open results file for reading." );
if ( !$respondentFile || !$answerFile )
	die( "Could not open an output file for writing." );

// throw away the header line
$resultsLine = fgetcsv( $resultsFile );

// in the results file, answers begin after 10 fields
$answersOffset = $whereToBreak;

// for each line of the answers file, we will do this:
while ( $resultsLine = fgetcsv( $resultsFile ) )
{
	// put the respondent portion of the results line into its own array
	$respondentInfo = array_slice( $resultsLine, 0, $answersOffset );
	// put the answer portion of the results line into its own array
	$answers = array_slice( $resultsLine, $answersOffset );

	// store the respondent id since it will key both output sets together
	$respondentID = $respondentInfo[0];

	//output the respondent info to the respondents file
	fputcsv( $respondentFile, $respondentInfo );
	
	// output each answer to one line of the answers file
	// questions start with number 1
	$questionID = 1;

	foreach( $answers as $answer )
	{
		fputcsv( $answerFile, array( $respondentID, $questionID, $answer ) );
		$questionID++;
	}
} // we are done with all lines

// close our files
fclose( $resultsFile );
fclose( $answerFile );
fclose( $respondentFile );

?>