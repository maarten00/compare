<?php

namespace App\Http\Controllers;

use App\Events\PageProcessed;
use App\Objects\MatchedPage;
use App\Objects\PageResults;
use App\Objects\ProcessedPage;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Import\Standard\Lexer;
use Illuminate\Http\Request;

//TODO: This sucks. We should try to read the headers from the CSV or have the user specify which columns to use and how.
const id                = 0;
const title             = 1;
const content           = 2;
const postType          = 1;
const percentageToMatch = 65;

class CompareController extends Controller {

	public function show() {
		return view( 'welcome' );
	}

	public function process( Request $request ) {
		// TODO:Refactor long tasks into queued command
		//Dirty hack because the app is slow.
		ini_set( 'max_execution_time', 900 );
		$comparedFromData = [];
		$compareToData    = [];
		$compare          = [];

		$config = new LexerConfig();
		//TODO: Allow use to choose delimiter and enclosure and headerine setting, better yet, try to detect settings.
		$config->setDelimiter( ';' );
		$config->setEnclosure( '"' );
		$config->setIgnoreHeaderLine( true );
		$importer = new Lexer( $config );

		$interpreterOne = new Interpreter();
		$interpreterOne->addObserver( function ( array $row ) use ( &$comparedFromData ) {
			$comparedFromData[] = $row;
		} );

		$compareFromFile = $request->file( 'compareFrom' );
		$importer->parse( $compareFromFile->path(), $interpreterOne );

		$interpreterTwo = new Interpreter();
		$interpreterTwo->addObserver( function ( array $row ) use ( &$compareToData ) {
			$compareToData[] = $row;
		} );

		$compareToFile = $request->file( 'compareTo' );
		$importer->parse( $compareToFile->path(), $interpreterTwo );

		foreach ( $comparedFromData as $key => $comparedSite ) {
			if ( $comparedSite [ content ] == '' ) {
				continue;
			}

			foreach ( $compareToData as $compareTo ) {
				if ( $compareTo[ content ] == '' ) {
					continue;
				}

				$calculated = $this->_compareThem( $comparedSite[ content ], $compareTo[ content ] );
				if ( $calculated > percentageToMatch ) {
					$compare[ $comparedSite[ id ] ][] = new MatchedPage($compareTo[id], $compareTo[title], $compareTo[postType], $calculated);
				}

			}
			unset( $comparedFromData[ $key ] );
			if ( isset( $compare[ $comparedSite[ id ] ] ) ) {
				$processedPage = new ProcessedPage($comparedSite[ id ], $comparedSite[title], $comparedSite[postType]);
				$pageResults = new PageResults($compare[ $comparedSite[ id ] ]);
				event(new PageProcessed($processedPage, $pageResults));
			}
		}
	}

	private function _compareThem( $str_1, $str_2 ) {

		$str_1 = trim( strtolower( $str_1 ) );
		$str_2 = trim( strtolower( $str_2 ) );

		similar_text( $str_1, $str_2, $percentage );

		$formated_percents = number_format( $percentage );

		return intval( $formated_percents );

	}

}
