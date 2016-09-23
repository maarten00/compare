<?php

namespace App\Http\Controllers;

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Import\Standard\Lexer;
use Illuminate\Http\Request;

const id                = 0;
const title             = 1;
const content           = 2;
const postType          = 3;
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

		ob_start();
		echo sprintf( '<h3>Results for %s</h3>', $compareFromFile->getClientOriginalName() );
		echo sprintf( '<table><tr><th><b>%s Page</b></th><th><b>Matching pages in %s</b></th></tr>',
			$compareFromFile->getClientOriginalName(), $compareToFile->getClientOriginalName() );
		//Nasty.
		//TODO: Refactor into use of Laravel events + pusher API to push results to front-end.
		ob_flush();
		flush();

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
					$compare[ $comparedSite[ id ] ][ $compareTo[ id ] ] = [
						'id'         => $compareTo[ id ],
						'title'      => $compareTo[ title ],
						'postType'   => $compareTo[ postType ],
						'percentage' => $calculated
					];
				}

			}
			unset( $comparedFromData[ $key ] );
			if ( isset( $compare[ $comparedSite[ id ] ] ) ) {
				$this->_printRow( $comparedSite, $compare[ $comparedSite[ id ] ] );
			}
		}
		echo "</table>";
		ob_flush();
		flush();
		ob_end_flush();
	}

	private function _compareThem( $str_1, $str_2 ) {

		$str_1 = trim( strtolower( $str_1 ) );
		$str_2 = trim( strtolower( $str_2 ) );

		similar_text( $str_1, $str_2, $percentage );

		$formated_percents = number_format( $percentage );

		return intval( $formated_percents );

	}

	private function _printRow( array $comparedSiteData, array $matchingPages ) {
		echo sprintf( '<tr style="border: 1px solid black;"><td style="border: 1px solid black;">%2$s(ID: %1$d) [Posttype: %3$s]</td><td style="border: 1px solid black;">',
			$comparedSiteData[ id ], $comparedSiteData[ title ], $comparedSiteData[ postType ] );
		foreach ( $matchingPages as $matchingPage ) {
			echo sprintf( '2$s: %3$d%% (ID: %1$s) [Posttype: %4$s]<br>', $matchingPage['id'], $matchingPage['title'],
				$matchingPage['percentage'], $matchingPage['postType'] );
		}
		echo "</td></tr>";
		ob_flush();
		flush();
	}
}
