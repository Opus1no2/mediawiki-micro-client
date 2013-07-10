<?php
/**
 *
 * Micro Client for MediaWiki REST API
 *
 * @Author: Travis Tillotson <tillotson.travis@gmail.com>
 */
class MediaWiki
{
    /**
     * @var array $option
     */
    public $option;
    /**
     * @var string $method
     */
    public $method = 'GET';
    /**
     * @var string $_agent
     */
    private $_agent = 'Micro-API-Client';
    
    const FORMAT    = 'json'; 
    const END_POINT = 'http://en.wikipedia.org/w/api.php?';
    
    /**
     * @var array $post_methods
     */
    public $post_methods = array(
        'login', 'createaccount', 'checkuser', 'purge',
        'setnotificationtimestamp', 'rollback', 'delete',
        'undelete', 'protect', 'block', 'unblock', 'move',
        'edit', 'upload', 'filerevert', 'emailuser', 'watch',
        'patrol', 'import', 'userrights', 'options', 'transcodereset',
        'emailcapture', 'deleteglobalaccount', 'setglobalaccountstatus',
        'abusefilterunblockautopromote', 'articlefeedbackv5-set-status',
        'articlefeedbackv5-add-flag-note', 'articlefeedbackv5-flag-feedback', 
        'articlefeedbackv5', 'visualeditor', 'markashelpful',
        'pagetriageaction', 'pagetriagetagging', 'deleteeducation',
        'enlist', 'refresheducation', 'stabilize', 'review', 'reviewactivity'
        
    );
    
    /**
     *
     * Per documentation - only json will be supported in the future
     *
     * @return void
     */
    public function __construct()
    {
        $this->option['format'] = self::FORMAT;
    }
    
    /**
     *
     * Using this to create an interface based on the API action requested
     *
     * @param string $m
     * @param array $data
     *
     * @return string
     *
     * @throws RunTimeException
     */
    public function __call($m, array $data)
    {
        $this->option['action'] = $m;
        
        if (in_array($m, $this->post_methods)) {
            $this->method = 'POST';
        }
        if (self::_is_assoc($data[0])) {
            foreach ($data[0] as $k => $v) {
                $this->option[$k] = $v;
            }
        } else {
            throw new RunTimeException(
                '$data must be an associative array'
            );
        }
        return $this->_request();
    }
    
    /**
     *
     * Create and execute HTTP request
     *
     * @return string
     */
    private function _request()
    {
        $ch = curl_init();
        
        $url = self::END_POINT;
        $url .= http_build_query($this->option);
        
        if (strtoupper($this->method) == 'POST') {
            $url = self::END_POINT;
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->option);    
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->_agent); 
        $out = curl_exec($ch);
        curl_close($ch);
        
        return $out;
    }
    
    /**
     *
     * Allow user agent to be set
     *
     * @param string $agent
     *
     * @return void
     */
    public function setUserAgent($agent)
    {
        $this->_agent = $agent;
    }
    
    /**
     *
     * Util function to check if array is associative
     *
     * @param array $array
     *
     * @return bool
     */
    private static function _is_assoc(array $array) 
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}