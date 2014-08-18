<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>PHP BOM Finder</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
		body { font-family: arial, verdana, sans-serif }
		p { margin-left: 20px }
	</style>
</head>
<body>
	<h1>
		PHP und HTML Dateien mit UTF-8 BOM</h1>
	<p>

<?php
// If your Hoster limited the Time to php Scripts remove the // in the following line.
//set_time_limit(10);
$total = CheckDir( './' ) ;

echo '<br /> Anzahl der Dateien mit UTF-8 BOM: ', $total ;

function CheckDir( $sourceDir )
{
	$counter = 0 ;

	$sourceDir = FixDirSlash( $sourceDir ) ;

	// Copy files and directories.
	$sourceDirHandler = opendir( $sourceDir ) ;

	while ( $file = readdir( $sourceDirHandler ) )
	{
		// Skip ".", ".." and hidden fields (Unix).
		if ( substr( $file, 0, 1 ) == '.' )
			continue ;

		$sourcefilePath = $sourceDir . $file ;

		if ( is_dir( $sourcefilePath ) )
		{
			$counter += CheckDir( $sourcefilePath ) ;
		}

		if ( !is_file( $sourcefilePath ) || (@GetFileExtension( $sourcefilePath ) != 'php' && @GetFileExtension( $sourcefilePath ) != 'html') || !CheckUtf8Bom( $sourcefilePath ) )
			continue ;

		echo $sourcefilePath, '<br />' ;

		$counter++ ;
	}

	return $counter ;
}

function FixDirSlash( $dirPath )
{
	$dirPath = str_replace( '\\', '/', $dirPath ) ;

	if ( substr( $dirPath, -1, 1 ) != '/' )
		$dirPath .= '/' ;

	return $dirPath ;
}

function GetFileExtension( $filePath )
{
	$info = pathinfo( $filePath ) ;
	return $info['extension'] ;
}

function CheckUtf8Bom( $filePath )
{
	$data = file_get_contents( $filePath ) ;

	return ( substr( $data, 0, 3 ) == "\xEF\xBB\xBF" ) ;
}
?>

</p>
</body>
</html>
