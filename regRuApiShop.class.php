<?php
  include_once('regRuApi.class.php');
  /**
   * Class for getting shop.reg.ru lots data 
   */

	class regRuApiShop extends regRuApi{

    /**
     * @var $settingQuery Array Wшер data for creating query to API for getting data with lots   
     */
		private $settingQuery = array(
			'username' => '',
			'password' => '',
			'pg' => 0,
			'itemsonpage' => 500,
			'show_my_lots' => 1,
			'output_content_type' => 'plain',
    );
    
    private $status = array(
      'success' => 'success',
      'fail' => 'error',
    );

    private $error = array(
      'ERROR_GET_COUNT_LOTS' => 'error_get_count_lots. Restart script or check internet connection',
    );
    
    /**
     * @var $lots Array with data from shop.reg.ru
     *        => allLots Array all urls in shop
     *        => onlineStatusLots Array urls with online status 
     *        => offlineStatusLots Array urls with offline status 
     *        => countLots Integer total count urls in shop
     */
		public $lots = array(
      'allLots' => [],
      'onlineStatusLots' => [],
      'offlineStatusLots' => [],
      'countLots' => 0,
    );
    
    /**
     * @var $queryApiUrl String url for send query to API
     */
		private $queryApiUrl = 'https://api.reg.ru/api/regru2/shop/get_lot_list';

    /**
     * Init login and password.
     * @param $login String Login reg.ru
     * @param $pwd String Password reg.ru
     * @return void
     */
		public function __construct($login, $pwd, $countLotsPerPage=500){
			$this->settingQuery->login = $login;
			$this->settingQuery->password = $pwd;
			$this->settingQuery->itemsonpage = $countLotsPerPage;
    }

   
    
    /**
     * Get total count lots in shop.reg.ru. If query return with error - stop working and write error message.
     * @return $lots->countLots Integer Total count lots in shop.reg.ru
     */
    private function getCountUrlsInShop(){
      $answerData = $this->sendQueryToApi($this->settingQuery);

      if ($answerData->result == $this->status->success){
        $lots->countLots = int($answerData->answer->lots_cnt);
        return $lots->countLots;
      }else{
        die($this->error->ERROR_GET_COUNT_LOTS);
      }
    }

    /**
     * import lots to $this->lots object with filtration by status
     * @param $dataLots Object Answer from API query with data lots
     * @return void
     */
    private function importExistedLots($dataLots){
      foreach ($dataLots as $dataLot){
        array_push($this->lots->allLots, $dataLot->dname_puny);
        if ($dataLot->dname_puny->is_online){
          array_push($this->lots->onlineStatusLots, $dataLot->dname_puny);
        }else{
          array_push($this->lots->offlineStatusLots, $dataLot->dname_puny);
        }
      }
    }

    /**
     * Get all accounts lots from shop.reg.ru
     * @return $this->lots Array with data lots
     */
    public function getLotsInShop(){
      $countLots = $this->getCountUrlsInShop();

      for ($i = 0; $i < round($countLots/$this->settingQuery->itemsonpage); $i++){
        $dataLots = $this->getLotsDataFromPage($i);
        $this->importExistedLots($dataLots);
      }
      return $this->lots;
    }

    /**
     * Get Lots data from page answer by API query
     * @param $page Integer Page data for getting information
     * @return $dataLots  Array Data lots from api return.
     */
		private function getLotsDataFromPage($page){
      $this->settingQuery->pg = $page;
      $answerData = $this->sendQueryToApi($this->settingQuery);

      if ($answerData->result == $this->status->success){
        return $answerData->answer->lots;
      }else{
        die($this->error->ERROR_GET_COUNT_LOTS);
      }     
		}
	
	}
?>