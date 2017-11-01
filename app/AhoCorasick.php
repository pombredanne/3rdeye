<?php
/**
ORIGINAL
 * Represents a finite state machine that can find all occurrences
 * of a set of search keywords in a body of text.
 *
 * The time it takes to construct the finite state machine is
 * proportional to the sum of the lengths of the search keywords.
 * Once constructed, the machine can locate all occurences of all
 * search keywords in a body of text in a single pass, making exactly
 * one state transition per input character.
 */
namespace App;

use Illuminate\Support\Facades\DB;


class AhoCorasick {

	/** @var string[] The set of keywords to be searched for. **/
	protected $keywords = array();

	/** @var int The number of possible states of the string-matching finite state machine. **/
	protected $numStates = 1;

	/** @var array Mapping of states to outputs. **/
	protected $outputs = array();

	/** @var array Mapping of failure transitions. **/
	protected $failueArray = array();

	/** @var array Mapping of success transitions. **/
	protected $gotoArray = array();



	public function __construct( array $keywords ) {
		foreach ( $keywords as $keyword ) {
			if ( $keyword !== '' ) {
				$this->keywords[$keyword] = strlen( $keyword );
			}
		}

		if ( !$this->keywords ) {
			//trigger_error( __METHOD__ . ': The set of search keywords is empty.', E_USER_WARNING );
			// Unreachable 'return' when PHPUnit detects trigger_error
			return; // @codeCoverageIgnore
		}

		$this->computeGoto();
		$this->computeFailure();
	}


	/**
	 * Accessor for the search keywords.
	 *
	 * @return string[] Search keywords.
	 */
	public function getKeywords() {
		return array_keys( $this->keywords );
	}


	/**
	 * Map the current state and input character to the next state.
	 *
	 * @param int $currentState The current state of the string-matching
	 *  automaton.
	 * @param string $inputChar The character the string-matching
	 *  automaton is currently processing.
	 * @return int The state the automaton should transition to.
	 */
	public function nextState( $currentState, $inputChar ) {
		$initialState = $currentState;
		while ( true ) {
			$transitions =& $this->gotoArray[$currentState];
			if ( isset( $transitions[$inputChar] ) ) {
				$nextState = $transitions[$inputChar];
				// Avoid failure transitions next time.
				if ( $currentState !== $initialState ) {
					$this->gotoArray[$initialState][$inputChar] = $nextState;
				}
				return $nextState;
			}
			if ( $currentState === 0 ) {
				return 0;
			}
			$currentState = $this->failueArray[$currentState];
		}
		// Unreachable outside 'while'
	} // @codeCoverageIgnore


	/**
	 * Locate the search keywords in some text.
	 *
	 * @param string $text The string to search in.
	 * @return array[] An array of matches. Each match is a vector
	 *  containing an integer offset and the matched keyword.
	 *
	 * @par Example:
	 * @code
	 *   $keywords = new MultiStringMatcher( array( 'ore', 'hell' ) );
	 *   $keywords->searchIn( 'She sells sea shells by the sea shore.' );
	 *   // result: array( array( 15, 'hell' ), array( 34, 'ore' ) )
	 * @endcode
	 */
	public function searchIn( $reportID, $refidarr, $arr , $title , $author, $school ) {
        
        $parser = new \Smalot\PdfParser\Parser();
        
        $state = 0;
        $results = array();
          
        for ($x = 0; $x < sizeof($arr); $x++) {
           
            $pdfp = $parser->parseFile($arr[$x]);

            $_text = strtolower($pdfp->getText());
            //if ( !$this->searchKeywords || $text === '' ) {
            if ( $_text === '' ) {
                return array();  // fast path
            }

            $pages  = $pdfp->getPages();
 
            $pageNumber = 1;

            // Loop over each page to extract text.
            foreach ($pages as $page) {
                
                $text = preg_replace('/\s+/', ' ',strtolower(preg_replace('/(\r\n|\r|\n)+/', "", trim($page->getText())))); //remove multiple spaces and replace with one space
                
                $text = filter_var($text, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
                
                $length = strlen( $text );

                for ( $i = 0; $i < $length; $i++ ) {
                    $ch = $text[$i];
                    $state = $this->nextState( $state, $ch );
                    foreach ( $this->outputs[$state] as $match ) {

                        $offset = $i - $this->keywords[$match] + 1;

						//save ptoperties to DB
                        /*
                        if($reportID != 0){

                        	DB::table('searchresults')->insert(
						    	["report_id" => $reportID, "reference_id" => $refidarr[$x], "match" => $match , "page" => $pageNumber, "offset" => $offset]
							);
                        }
                        */
                       
                         //save matches as json
                        $results[] = array( "book" => $x, "match" => $match , "page" => $pageNumber, "link" => $arr[$x], "title" => $title[$x], "author" => $author[$x], "offset" => $offset, "school" => $school);
                    }
                }
                
                $pageNumber = $pageNumber + 1;
            }
        }
   
		return $results;		//return matches (json)
	}


	/**
	 * Get the state transitions which the string-matching automaton
	 * shall make as it advances through input text.
	 *
	 * Constructs a directed tree with a root node which represents the
	 * initial state of the string-matching automaton and from which a
	 * path exists which spells out each search keyword.
	 */
	protected function computeGoto() {
		$this->gotoArray= array( array() );
		$this->outputs = array( array() );
		foreach ( $this->keywords as $keyword => $length ) {
			$state = 0;
			for ( $i = 0; $i < $length; $i++ ) {
				$ch = $keyword[$i];
				if ( !empty( $this->gotoArray[$state][$ch] ) ) {
					$state = $this->gotoArray[$state][$ch];
				} else {
					$this->gotoArray[$state][$ch] = $this->numStates;
					$this->gotoArray[] = array();
					$this->outputs[] = array();
					$state = $this->numStates++;
				}
			}

			$this->outputs[$state][] = $keyword;
		}
	}


	/**
	 * Get the state transitions which the string-matching automaton
	 * shall make when a partial match proves false.
	 */
	protected function computeFailure() {
		$queue = array();
		$this->failueArray = array();

		foreach ( $this->gotoArray[0] as $ch => $toState ) {
			$queue[] = $toState;
			$this->failueArray[$toState] = 0;
		}

		while ( true ) {
			$fromState = array_shift( $queue );
			if ( $fromState === null ) {
				break;
			}
			foreach ( $this->gotoArray[$fromState] as $ch => $toState ) {
				$queue[] = $toState;
				$state = $this->failueArray[$fromState];

				while ( $state !== 0 && empty( $this->gotoArray[$state][$ch] ) ) {
					$state = $this->failueArray[$state];
				}

				if ( isset( $this->gotoArray[$state][$ch] ) ) {
					$noState = $this->gotoArray[$state][$ch];
				} else {
					$noState = 0;
				}

				$this->failueArray[$toState] = $noState;
				$this->outputs[$toState] = array_merge(
					$this->outputs[$toState], $this->outputs[$noState] );
			}
		}
	}
}
