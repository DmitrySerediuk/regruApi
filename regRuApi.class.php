<?
  /**
   * Basic class for reg.ru API query
   */

  class regRuApi {
    /**
     * @var $queryApiUrl String url for send query to API
     */
		private $queryApiUrl = 'https://api.reg.ru/api/regru2/shop/get_lot_list';

     /**
     * Form and send query to API with $queryData
     * @param $queryData Array Data for api query
     * @return $answerData Object Return query answer
     */
    private function sendQueryToApi($queryData){
      $queryUrl = $this->$queryUrl.http_build_query($queryData);
      return $answerData = json_decode(file_get_contents($queryUrl));
    }
  }
?>