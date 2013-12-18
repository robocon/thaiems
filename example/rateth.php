<?php
/**
 * // Set supply weight
 * $rate = new RateTH(1167);
 * // Check an error before show EMS price
 * if(!$rate->getError()){
 *     // And get EMS price rate
 *     $price = $rate->getPrice();
 * }
 */
class RateTH{
    protected $_weight;
    static $_feed = "thaiems.php";
    protected $_ems_type = array(0=>"ems", "normal");
    protected $_error = FALSE;
    protected $_data;

    function __construct(){
        $this->load_data();
    }

    private function load_data(){
        if(is_array($this->_data)){
            return true;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$_feed);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $this->_data = json_decode(curl_exec($ch), TRUE);
        curl_close($ch);
    }

    public function set_weight($weight = 0){
        $this->_weight = intval($weight);
    }
    
    /**
     * Get price from json
     */
    public function get_price($type=0){
        
        $pre_ems = $this->_data;
        $emstype = $this->_ems_type[$type];

        $ems = $pre_ems[$emstype];
        $ems_count = count($ems);
        
        if($this->_weight === 0){
            $this->_error = "Your weight can not be zero, please check your item again.";
        }else if($this->_weight > $ems[$ems_count-1]['weight']){
            $this->_error = "Your weight is over the maximum limit.";
        }else{
            for($i=0; $i<$ems_count; $i++){
                $item = $ems[$i];
                $prevent_item = $ems[$i-1];
                if($prevent_item!==NULL && $this->_weight>$ems['0']['weight']){

                    if($this->_weight<=$item['weight'] && $this->_weight>$prevent_item['weight']){
                        $price = $item['price'];
                    }
                }else if($prevent_item===NULL && $this->_weight===$ems['0']['weight']){
                    $price = $item['price'];
                }
            } // End for
        }

        return $price;
    }
    
    /**
     * Check an error
     * 
     * @return boolean
     */
    public function getError(){
        if($this->_error!==FALSE){
            echo "<div>".$this->_error."</div>";
            return TRUE;
        }else{
            return FALSE;
        }
        
    }
}