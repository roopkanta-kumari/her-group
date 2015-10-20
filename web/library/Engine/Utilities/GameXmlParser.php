<?php
class Engine_Utilities_GameXmlParser{

	private static $_instance = null;
        public $_NFL = null;


        //Prevent any oustide instantiation of this class
	private function  __construct() { 
		
            $this->_NFL = "http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/nfl-shedule";
            $this->_MLB = "http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/baseball/mlb_shedule";
            $this->_NBA = "http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/bsktbl/nba-shedule";
            $this->_NHL = "http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/nhl-shedule";
	} 
	
	private function  __clone() { } //Prevent any copy of this object
	
	public static function getInstance(){
		if( !is_object(self::$_instance) )  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
		self::$_instance = new Engine_Utilities_GameXmlParser();
		return self::$_instance;
	}
        
        public function getGameLists(){
            if(func_num_args() > 0){
                $gameName = func_get_arg(0);
                switch ($gameName) {
                    case 'NFL':
                            $client = new Zend_Http_Client($this->_NFL);      
                            $response = $client->request();
                            $data = simplexml_load_string($response->getBody());
                            $matchesArray = array();
                            
                            foreach ($data as $tvalue) {
                               $gameTournamentName = (string)$tvalue['name'];
                               $gameTournamentId   = (string)$tvalue['id'];
                               foreach ($tvalue as $wvalue) {
                                 $gameWeekName   = (string)$wvalue['name'];
                                 
                                         if(isset($wvalue->matches)){
                                               foreach($wvalue->matches as $matches){

                                                   $matchesDate = $matches['date'];


                                                   $matchFormatDate = strtotime(date('Y-m-d',strtotime($matches['formatted_date'])));
                                                   $matchesArray[$matchFormatDate]['match_on'] = (string)$matchesDate;
                                                   $matchesArray[$matchFormatDate]['timezone'] = (string)$matches['timezone'];
                                                   $matchesArray[$matchFormatDate]['formatted_date'] = (string)$matches['formatted_date'];
                                                   $matchesArray[$matchFormatDate]['game_tournament_name'] = $gameTournamentName;
                                                   $matchesArray[$matchFormatDate]['game_tournament_id'] = $gameTournamentId;
                                                   $matchesArray[$matchFormatDate]['game_week_name'] = $gameWeekName;

                                                   if(isset($matches->match)){
                                                       $matchesArray[$matchFormatDate]['match_count'] = count($matches->match);

                                                       $i = 0;
                                                       foreach($matches->match as $match){
                                                           $matchesArray[$matchFormatDate]['match'][$i]['id'] = (string)$match['id'];
                                                           $matchesArray[$matchFormatDate]['match'][$i]['time'] = (string)$match['time'];
                                                           $matchesArray[$matchFormatDate]['match'][$i]['live_id'] = (string)$match['live_id'];
                                                           $matchesArray[$matchFormatDate]['match'][$i]['formatted_date'] = (string)$match['formatted_date'];
                                                           $matchesArray[$matchFormatDate]['match'][$i]['status'] = (string)$match['status'];

                                                           $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['name'] = (string)$match->hometeam['name'];
                                                           $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['hits'] = (string)$match->hometeam['hits'];
                                                           $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['totalscore'] = (string)$match->hometeam['totalscore'];
                                                           $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['id'] = (string)$match->hometeam['id'];

                                                           $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['name'] = (string)$match->awayteam['name'];
                                                           $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['hits'] = (string)$match->awayteam['hits'];
                                                           $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['totalscore'] = (string)$match->awayteam['totalscore'];
                                                           $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['id'] = (string)$match->awayteam['id'];

                                                           $i++;
                                                       }
                                                   }

                                               }
                                           }
                                 
                                 
                                 
                                 
                               }
                            }
                                                       
                            if(isset($data->category->matches)){
                                foreach($data->category->matches as $matches){

                                    $matchesDate = $matches['date'];


                                    $matchFormatDate = strtotime(date('Y-m-d',strtotime($matches['formatted_date'])));
                                    $matchesArray[$matchFormatDate]['match_on'] = (string)$matchesDate;
                                    $matchesArray[$matchFormatDate]['timezone'] = (string)$matches['timezone'];
                                    $matchesArray[$matchFormatDate]['formatted_date'] = (string)$matches['formatted_date'];
                                    $matchesArray[$matchFormatDate]['game_category_name'] = $gameCategoryName;
                                    $matchesArray[$matchFormatDate]['game_category_id'] = $gameCategoryId;

                                    if(isset($matches->match)){
                                        $matchesArray[$matchFormatDate]['match_count'] = count($matches->match);

                                        $i = 0;
                                        foreach($matches->match as $match){
                                            $matchesArray[$matchFormatDate]['match'][$i]['id'] = (string)$match['id'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['time'] = (string)$match['time'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['live_id'] = (string)$match['live_id'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['formatted_date'] = (string)$match['formatted_date'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['status'] = (string)$match['status'];

                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['name'] = (string)$match->hometeam['name'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['hits'] = (string)$match->hometeam['hits'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['totalscore'] = (string)$match->hometeam['totalscore'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['id'] = (string)$match->hometeam['id'];

                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['name'] = (string)$match->awayteam['name'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['hits'] = (string)$match->awayteam['hits'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['totalscore'] = (string)$match->awayteam['totalscore'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['id'] = (string)$match->awayteam['id'];

                                            $i++;
                                        }
                                    }

                                }
                            } 
                            return $matchesArray;
                        
                        break;
                    case 'MLB':
                            $client = new Zend_Http_Client($this->_MLB);      
                            $response = $client->request();
                            $data = simplexml_load_string($response->getBody());
                            $matchesArray = array();


                            $gameCategoryName = (string)$data->category['name'];
                            $gameCategoryId   = (string)$data->category['id'];

                            if(isset($data->category->matches)){
                                foreach($data->category->matches as $matches){

                                    $matchesDate = $matches['date'];


                                    $matchFormatDate = strtotime(date('Y-m-d',strtotime($matches['formatted_date'])));
                                    $matchesArray[$matchFormatDate]['match_on'] = (string)$matchesDate;
                                    $matchesArray[$matchFormatDate]['timezone'] = (string)$matches['timezone'];
                                    $matchesArray[$matchFormatDate]['formatted_date'] = (string)$matches['formatted_date'];
                                    $matchesArray[$matchFormatDate]['game_category_name'] = $gameCategoryName;
                                    $matchesArray[$matchFormatDate]['game_category_id'] = $gameCategoryId;

                                    if(isset($matches->match)){
                                        $matchesArray[$matchFormatDate]['match_count'] = count($matches->match);

                                        $i = 0;
                                        foreach($matches->match as $match){
                                            $matchesArray[$matchFormatDate]['match'][$i]['id'] = (string)$match['id'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['time'] = (string)$match['time'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['live_id'] = (string)$match['live_id'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['formatted_date'] = (string)$match['formatted_date'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['status'] = (string)$match['status'];

                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['name'] = (string)$match->hometeam['name'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['hits'] = (string)$match->hometeam['hits'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['totalscore'] = (string)$match->hometeam['totalscore'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['id'] = (string)$match->hometeam['id'];

                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['name'] = (string)$match->awayteam['name'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['hits'] = (string)$match->awayteam['hits'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['totalscore'] = (string)$match->awayteam['totalscore'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['id'] = (string)$match->awayteam['id'];

                                            $i++;
                                        }
                                    }

                                }
                            }
                            return $matchesArray;
                        break;
                    case 'NBA':
                            $client = new Zend_Http_Client($this->_NBA);      
                            $response = $client->request();
                            $data = simplexml_load_string($response->getBody());
                            $matchesArray = array();

                            $gameCategoryName = (string)$data['league'];
                            $gameCategoryId   = (string)$data['id'];

                            if(isset($data->matches)){
                                foreach($data->matches as $matches){

                                    $matchesDate = $matches['date'];


                                    $matchFormatDate = strtotime(date('Y-m-d',strtotime($matches['formatted_date'])));
                                    $matchesArray[$matchFormatDate]['match_on'] = (string)$matchesDate;
                                    $matchesArray[$matchFormatDate]['timezone'] = (string)$matches['timezone'];
                                    $matchesArray[$matchFormatDate]['formatted_date'] = (string)$matches['formatted_date'];
                                    $matchesArray[$matchFormatDate]['game_category_name'] = $gameCategoryName;
                                    $matchesArray[$matchFormatDate]['game_category_id'] = $gameCategoryId;

                                    if(isset($matches->match)){
                                        $matchesArray[$matchFormatDate]['match_count'] = count($matches->match);
                                        $i = 0;
                                        foreach($matches->match as $match){
                                            $matchesArray[$matchFormatDate]['match'][$i]['id'] = (string)$match['id'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['time'] = (string)$match['time'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['formatted_date'] = (string)$match['formatted_date'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['status'] = (string)$match['status'];

                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['name'] = (string)$match->hometeam['name'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['totalscore'] = (string)$match->hometeam['totalscore'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['id'] = (string)$match->hometeam['id'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['q1'] = (string)$match->hometeam['q1'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['q2'] = (string)$match->hometeam['q2'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['q3'] = (string)$match->hometeam['q3'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['q4'] = (string)$match->hometeam['q4'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['ot'] = (string)$match->hometeam['ot'];

                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['name'] = (string)$match->awayteam['name'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['totalscore'] = (string)$match->awayteam['totalscore'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['id'] = (string)$match->awayteam['id'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['q1'] = (string)$match->awayteam['q1'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['q2'] = (string)$match->awayteam['q2'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['q3'] = (string)$match->awayteam['q3'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['q4'] = (string)$match->awayteam['q4'];
                                            $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['ot'] = (string)$match->awayteam['ot'];

                                            $i++;
                                        }
                                    }

                                }
                            }
                            return $matchesArray;
                        break;
                    case 'NHL':
                            $client = new Zend_Http_Client($this->_NHL);      
                            $response = $client->request();
                            $data = simplexml_load_string($response->getBody());
                            $matchesArray = array();
                            if(isset($data->matches)){
                                foreach($data->matches as $matches){

                                    $matchDate          = (string)$matches['date'];
                                    $matchTimezone      = (string)$matches['timezone'];
                                    $matchFormattedDate = (string)$matches['formatted_date'];
                                    $gameCategoryName   = (string)$data['league'];
                                    $gameCategoryId     = (string)$data['id'];

                                    if(strtotime($matchFormattedDate) >= strtotime(date('d.m.Y'))){

                                        $matchFormatDate = strtotime(date('Y-m-d',strtotime($matchFormattedDate)));
                                        $matchesArray[$matchFormatDate]['match_on'] = $matchDate;
                                        $matchesArray[$matchFormatDate]['timezone'] = $matchTimezone;
                                        $matchesArray[$matchFormatDate]['formatted_date'] = $matchFormattedDate;
                                        $matchesArray[$matchFormatDate]['game_category_name'] = $gameCategoryName;
                                        $matchesArray[$matchFormatDate]['game_category_id'] = $gameCategoryId;

                                       if (isset($matches->match)) {                         

                                          $i = 0;
                                          foreach ($matches->match as $match) {

                                              $matchesArray[$matchFormatDate]['match'][$i]['id'] = (string)$match['id'];
                                              $matchesArray[$matchFormatDate]['match'][$i]['time'] = (string)$match['time'];
                                              $matchesArray[$matchFormatDate]['match'][$i]['formatted_date'] = (string)$match['formatted_date'];
                                              $matchesArray[$matchFormatDate]['match'][$i]['status'] = (string)$match['status'];

                                              $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['name'] = (string)$match->hometeam['name'];
                                              $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['totalscore'] = (string)$match->hometeam['totalscore'];
                                              $matchesArray[$matchFormatDate]['match'][$i]['hometeam']['id'] = (string)$match->hometeam['id'];

                                              $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['name'] = (string)$match->awayteam['name'];
                                              $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['totalscore'] = (string)$match->awayteam['totalscore'];
                                              $matchesArray[$matchFormatDate]['match'][$i]['awayteam']['id'] = (string)$match->awayteam['id'];

                                              $i++;
                                          }
                                       }
                                    }

                                }
                            }
                            return $matchesArray;
                        break;

                    default:
                        break;
                }
            }
        }
        
        public function getPlayerLists(){
            
            if(func_num_args() > 0){
                
                
                $gameType = func_get_arg(0);
                $teamName = func_get_arg(1);
                $abbreviation = func_get_arg(2);
                $team = func_get_arg(3);
                                
                switch ($gameType) {
                    case 'NFL':
                        $url['ari'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/ari_rosters';
                        $url['atl'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/atl_rosters';
                        $url['bal'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/bal_rosters';
                        $url['buf'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/buf_rosters';
                        $url['car'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/car_rosters';
                        $url['chi'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/chi_rosters';
                        $url['cin'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/cin_rosters';
                        $url['cle'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/cle_rosters';
                        $url['dal'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/dal_rosters';
                        $url['den'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/den_rosters';
                        $url['det'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/det_rosters';
                        $url['gb']  = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/gb_rosters';
                        $url['hou'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/hou_rosters';
                        $url['ind'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/ind_rosters';
                        $url['jac'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/jac_rosters';
                        $url['kc']  = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/kc_rosters';
                        $url['mia'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/mia_rosters';
                        $url['min'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/min_rosters';
                        $url['no']  = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/no_rosters';
                        $url['nyg'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/nyg_rosters';
                        $url['nyj'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/nyj_rosters';
                        $url['oak'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/oak_rosters';
                        $url['phi'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/phi_rosters';
                        $url['pit'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/pit_rosters';
                        $url['sd_'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/sd_rosters';
                        $url['sea'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/sea_rosters';
                        $url['sf']  = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/sf_rosters';
                        $url['stl'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/stl_rosters';
                        $url['tb']  = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/tb_rosters';
                        $url['ten'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/ten_rosters';    
                        $url['wsh'] = 'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/football/wsh_rosters'; 
                        
                         $playerListArray = array();
                         
                         foreach ($url as $ukey => $uvalue) {
                             
                             if(is_array($teamName) && !empty($teamName)){
                                 
                                 if(array_search($ukey, $teamName)){
                                     
                                        $client = new Zend_Http_Client($uvalue);      
                                        $response = $client->request();
                                        $data = simplexml_load_string($response->getBody());
                                        
                                        foreach ($data as $dkey => $dvalue) {
                                            foreach ($dvalue as $pkey => $pvalue) {
                                                $playervalue['number']           = (string)$pvalue['number'];
                                                $playervalue['name']             = (string)$pvalue['name'];
                                                $playervalue['position']         = (string)$pvalue['position'];
                                                $playervalue['age']              = (string)$pvalue['age'];
                                                $playervalue['height']           = (string)$pvalue['height'];
                                                $playervalue['weight']           = (string)$pvalue['weight'];
                                                $playervalue['experience_years'] = (string)$pvalue['experience_years'];
                                                $playervalue['college']          = (string)$pvalue['college'];
                                                $playervalue['id']               = (string)$pvalue['id'];
                                                $playervalue['team_name']        = (string)$data['name'];
                                                
                                                // get first character of words in given string to create possition code
                                               
                                                $words = explode(" ", (string)$dvalue['name']);
                                                $acronym = "";
                                                foreach ($words as $w) {
                                                    $acronym .= $w[0];
                                                }                                            
                                                $playervalue['pos_code']        = $acronym;

                                                // create team name code

                                                $team_code = array_search((string)$data['name'], $abbreviation);
                                                $playervalue['team_code'] = $team_code;
                                            
                                                // add competitor team code
                                                $playervalue['team_vs'] = $team[$team_code];
                                                
                                                array_push($playerListArray, $playervalue);
                                            }
                                        } 
                                 }
                             }
                             
                                                        
                         }
                         
                         if(!empty($playerListArray)){
                             $playerListArray = array_values($playerListArray);
                             return $playerListArray;
                         }
                         
                           
                        break;
                    case 'MLB':


                        break;
                    case 'NBA':


                        break;
                    case 'NHL':
                                $url['ana'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/ana_rosters';
                                $url['atl'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/atl_rosters';
                                $url['bos'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/bos_rosters';
                                $url['buf'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/buf_rosters';
                                $url['car'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/car_rosters';
                                $url['cbs'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/cbj_rosters';
                                $url['cgy'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/cgy_rosters';
                                $url['chi'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/chi_rosters';
                                $url['col'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/col_rosters';
                                $url['dal'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/dal_rosters';
                                $url['det'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/det_rosters';
                                $url['edm'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/edm_rosters';
                                $url['fla'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/fla_rosters';
                                $url['la']  =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/la_rosters';
                                $url['min'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/min_rosters';
                                $url['mtl'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/mtl_rosters';
                                $url['nj']  =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/nj_rosters';
                                $url['nsh'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/nsh_rosters';
                                $url['nyi'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/nyi_rosters';
                                $url['nyr'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/nyr_rosters';
                                $url['ott'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/ott_rosters';
                                $url['phi'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/phi_rosters';
                                $url['phx'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/phx_rosters';
                                $url['pit'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/pit_rosters';
                                $url['sj']  =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/sj_rosters';
                                $url['stl'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/stl_rosters';
                                $url['tb']  =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/tb_rosters';
                                $url['tor'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/tor_rosters';
                                $url['van'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/van_rosters';
                                $url['wsh'] =  'http://www.goalserve.com/getfeed/f036325a28634e80b24f87f0dee66bca/hockey/wsh_rosters'; 
                        
                         $playerListArray = array();
                         
                         foreach ($url as $ukey => $uvalue) {
                             
                             if(is_array($teamName) && !empty($teamName)){
                                 
                                 //if(array_search($ukey, $teamName)){
                                  if(in_array($ukey, $teamName)){  
                                    $client = new Zend_Http_Client($uvalue);      
                                    $response = $client->request();
                                    $data = simplexml_load_string($response->getBody());
                                    
                                    foreach ($data as $dkey => $dvalue) {

                                        foreach ($dvalue as $pkey => $pvalue) {
                                            $playervalue['number']           = (string)$pvalue['number'];
                                            $playervalue['name']             = (string)$pvalue['name'];
                                            $playervalue['position']         = (string)$dvalue['name'];
                                            $playervalue['age']              = (string)$pvalue['age'];
                                            $playervalue['height']           = (string)$pvalue['height'];
                                            $playervalue['weight']           = (string)$pvalue['weight'];
                                            $playervalue['birth_place']      = (string)$pvalue['birth_place'];
                                            $playervalue['shot']             = (string)$pvalue['shot'];
                                            $playervalue['id']               = (string)$pvalue['id'];
                                            $playervalue['team_name']        = (string)$data['name'];
                                            
                                            // get first character of words in given string to create possition code
                                               
                                            $words = explode(" ", (string)$dvalue['name']);
                                            $acronym = "";
                                            foreach ($words as $w) {
                                                $acronym .= $w[0];
                                            }                                            
                                            $playervalue['pos_code']        = $acronym;
                                            
                                            // create team name code
                                            $team_code = array_search((string)$data['name'], $abbreviation);
                                            $playervalue['team_code'] = $team_code;
                                            
                                            // add competitor team code
                                            if(isset($team[$team_code])){
                                                $playervalue['team_vs'] = $team[$team_code];
                                            }else{
                                                $playervalue['team_vs'] = "";
                                            }
                                            
                                            
                                            array_push($playerListArray, $playervalue);
                                        }
                                    }  
                                 }
                             }
                                                       
                         }
                         
                         if(!empty($playerListArray)){
                             $playerListArray = array_values($playerListArray);
                             return $playerListArray;
                         }
                        break;                    

                    default:
                        break;
                }
                
                
                
            }
        }

        /**
         * Desc : Filter Array by searchkey and searchvalue
         * @param <String> $searchValue
         * @param <Array> $array
         * @param <String> $searchKey
         * @return <Array> $filtered
         */
        public function filterArray($searchValue,$array,$searchKey){
            
            if($searchValue != "" && $searchKey != ""){
                $filter = function($array) use($searchValue,$searchKey) { return $array[$searchKey] == $searchValue; };
                $filtered = array_filter($array, $filter);
                return $filtered;
            }
            
        }

}

?>