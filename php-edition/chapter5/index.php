<?php

$chapter_meta = array(
	'number' => 5,
	'title' => 'Manipulating Third Party Data',
	'summary' => ''
);

$examples = array(
	'ReconstructionUsingSQL' => 'A map of the towers owned and operated in Hawaii',
);

$code_listings = array(
	'01' => '/chapter5/ParsingCSVData/index.php.source',
	'02' => '/chapter5/sql_schema.txt.source#l5-2',
	'03' => '/chapter5/DataImport/index.phps.source',
	'04' => '/chapter5/ReconstructionUsingPHP/index.php.source',
	'05' => '/chapter5/ReconstructionUsingSQL/map_data.php.source#l5-5',
	'06' => '/chapter5/sql_schema.txt.source#l5-6',
	'07' => '/chapter5/scrape_me.html.source',
	'08' => '/chapter5/ScreenScraping/index.phps.source',
	'09' => '/chapter5/sql_schema.txt.source#l5-9',
);


$other_links = array(
	'http://wireless.fcc.gov/uls/data/complete/r_tower.zip' => 'FCC ASR database for the examples in Part 2',
	'http://wireless.fcc.gov/cgi-bin/wtb-transactions.pl#tow' => 'Nightly transactions for the FCC ASR database',
);

$further_reading = array(
	'http://wireless.fcc.gov/cgi-bin/wtb-datadump.pl' => 'Official documentation for the FCC ASR database',
	'http://www.php.net/fgets' => 'Official Documentation on fgets()',
	'http://www.apress.com/book/bookDisplay.html?bID=10017' => 'Beginning PHP and MySQL 5: From Novice to Professional, Second Edition, by W. Jason Gilmore',
	'http://ca.php.net/manual/en/function.array-merge.php' => 'Official Documentation for array_merge()',
	'http://dev.mysql.com/doc/refman/5.0/en/create-view.html' => 'Creating MySQL 5 Views',
	'http://dev.mysql.com/doc/refman/5.0/en/view-restrictions.html' => 'Limitations of MySQL 5 Views',
	'http://www.postgresql.org/docs/8.1/static/sql-createview.html' => 'Creating Views in PostgreSQL 8x',
);

include('../chapter.php');

?>
