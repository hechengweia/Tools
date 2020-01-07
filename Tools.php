<?php
/**
 * PHP 汉字转拼音
 * @author Jerryli(hzjerry@gmail.com)
 * @version V0.20140715
 * @package SPFW.core.lib.final
 * @global SEA_PHP_FW_VAR_ENV
 * @example
 *    echo pyFormat::encode('阿里巴巴科技有限公司', 'head', 'gbk'); //编码为拼音首字母
 *    echo pyFormat::encode('阿里巴巴科技有限公司', 'all', 'utf8'); //编码为全拼音
 */
class Tools  {
    private $authCodeKey    = '';
    private $apiKey         = '';//查询快递接口
    private $customer       = '';//查询快递接口
    /**
     * 拼音字符转换图
     * @var array
     */
    private static $_aMaps = array(
        'a'=>-20319,'ai'=>-20317,'an'=>-20304,'ang'=>-20295,'ao'=>-20292,
        'ba'=>-20283,'bai'=>-20265,'ban'=>-20257,'bang'=>-20242,'bao'=>-20230,'bei'=>-20051,'ben'=>-20036,'beng'=>-20032,'bi'=>-20026,'bian'=>-20002,'biao'=>-19990,'bie'=>-19986,'bin'=>-19982,'bing'=>-19976,'bo'=>-19805,'bu'=>-19784,
        'ca'=>-19775,'cai'=>-19774,'can'=>-19763,'cang'=>-19756,'cao'=>-19751,'ce'=>-19746,'ceng'=>-19741,'cha'=>-19739,'chai'=>-19728,'chan'=>-19725,'chang'=>-19715,'chao'=>-19540,'che'=>-19531,'chen'=>-19525,'cheng'=>-19515,'chi'=>-19500,'chong'=>-19484,'chou'=>-19479,'chu'=>-19467,'chuai'=>-19289,'chuan'=>-19288,'chuang'=>-19281,'chui'=>-19275,'chun'=>-19270,'chuo'=>-19263,'ci'=>-19261,'cong'=>-19249,'cou'=>-19243,'cu'=>-19242,'cuan'=>-19238,'cui'=>-19235,'cun'=>-19227,'cuo'=>-19224,
        'da'=>-19218,'dai'=>-19212,'dan'=>-19038,'dang'=>-19023,'dao'=>-19018,'de'=>-19006,'deng'=>-19003,'di'=>-18996,'dian'=>-18977,'diao'=>-18961,'die'=>-18952,'ding'=>-18783,'diu'=>-18774,'dong'=>-18773,'dou'=>-18763,'du'=>-18756,'duan'=>-18741,'dui'=>-18735,'dun'=>-18731,'duo'=>-18722,
        'e'=>-18710,'en'=>-18697,'er'=>-18696,
        'fa'=>-18526,'fan'=>-18518,'fang'=>-18501,'fei'=>-18490,'fen'=>-18478,'feng'=>-18463,'fo'=>-18448,'fou'=>-18447,'fu'=>-18446,
        'ga'=>-18239,'gai'=>-18237,'gan'=>-18231,'gang'=>-18220,'gao'=>-18211,'ge'=>-18201,'gei'=>-18184,'gen'=>-18183,'geng'=>-18181,'gong'=>-18012,'gou'=>-17997,'gu'=>-17988,'gua'=>-17970,'guai'=>-17964,'guan'=>-17961,'guang'=>-17950,'gui'=>-17947,'gun'=>-17931,'guo'=>-17928,
        'ha'=>-17922,'hai'=>-17759,'han'=>-17752,'hang'=>-17733,'hao'=>-17730,'he'=>-17721,'hei'=>-17703,'hen'=>-17701,'heng'=>-17697,'hong'=>-17692,'hou'=>-17683,'hu'=>-17676,'hua'=>-17496,'huai'=>-17487,'huan'=>-17482,'huang'=>-17468,'hui'=>-17454,'hun'=>-17433,'huo'=>-17427,
        'ji'=>-17417,'jia'=>-17202,'jian'=>-17185,'jiang'=>-16983,'jiao'=>-16970,'jie'=>-16942,'jin'=>-16915,'jing'=>-16733,'jiong'=>-16708,'jiu'=>-16706,'ju'=>-16689,'juan'=>-16664,'jue'=>-16657,'jun'=>-16647,
        'ka'=>-16474,'kai'=>-16470,'kan'=>-16465,'kang'=>-16459,'kao'=>-16452,'ke'=>-16448,'ken'=>-16433,'keng'=>-16429,'kong'=>-16427,'kou'=>-16423,'ku'=>-16419,'kua'=>-16412,'kuai'=>-16407,'kuan'=>-16403,'kuang'=>-16401,'kui'=>-16393,'kun'=>-16220,'kuo'=>-16216,
        'la'=>-16212,'lai'=>-16205,'lan'=>-16202,'lang'=>-16187,'lao'=>-16180,'le'=>-16171,'lei'=>-16169,'leng'=>-16158,'li'=>-16155,'lia'=>-15959,'lian'=>-15958,'liang'=>-15944,'liao'=>-15933,'lie'=>-15920,'lin'=>-15915,'ling'=>-15903,'liu'=>-15889,'long'=>-15878,'lou'=>-15707,'lu'=>-15701,'lv'=>-15681,'luan'=>-15667,'lue'=>-15661,'lun'=>-15659,'luo'=>-15652,
        'ma'=>-15640,'mai'=>-15631,'man'=>-15625,'mang'=>-15454,'mao'=>-15448,'me'=>-15436,'mei'=>-15435,'men'=>-15419,'meng'=>-15416,'mi'=>-15408,'mian'=>-15394,'miao'=>-15385,'mie'=>-15377,'min'=>-15375,'ming'=>-15369,'miu'=>-15363,'mo'=>-15362,'mou'=>-15183,'mu'=>-15180,
        'na'=>-15165,'nai'=>-15158,'nan'=>-15153,'nang'=>-15150,'nao'=>-15149,'ne'=>-15144,'nei'=>-15143,'nen'=>-15141,'neng'=>-15140,'ni'=>-15139,'nian'=>-15128,'niang'=>-15121,'niao'=>-15119,'nie'=>-15117,'nin'=>-15110,'ning'=>-15109,'niu'=>-14941,'nong'=>-14937,'nu'=>-14933,'nv'=>-14930,'nuan'=>-14929,'nue'=>-14928,'nuo'=>-14926,
        'o'=>-14922,'ou'=>-14921,
        'pa'=>-14914,'pai'=>-14908,'pan'=>-14902,'pang'=>-14894,'pao'=>-14889,'pei'=>-14882,'pen'=>-14873,'peng'=>-14871,'pi'=>-14857,'pian'=>-14678,'piao'=>-14674,'pie'=>-14670,'pin'=>-14668,'ping'=>-14663,'po'=>-14654,'pu'=>-14645,
        'qi'=>-14630,'qia'=>-14594,'qian'=>-14429,'qiang'=>-14407,'qiao'=>-14399,'qie'=>-14384,'qin'=>-14379,'qing'=>-14368,'qiong'=>-14355,'qiu'=>-14353,'qu'=>-14345,'quan'=>-14170,'que'=>-14159,'qun'=>-14151,
        'ran'=>-14149,'rang'=>-14145,'rao'=>-14140,'re'=>-14137,'ren'=>-14135,'reng'=>-14125,'ri'=>-14123,'rong'=>-14122,'rou'=>-14112,'ru'=>-14109,'ruan'=>-14099,'rui'=>-14097,'run'=>-14094,'ruo'=>-14092,
        'sa'=>-14090,'sai'=>-14087,'san'=>-14083,'sang'=>-13917,'sao'=>-13914,'se'=>-13910,'sen'=>-13907,'seng'=>-13906,'sha'=>-13905,'shai'=>-13896,'shan'=>-13894,'shang'=>-13878,'shao'=>-13870,'she'=>-13859,'shen'=>-13847,'sheng'=>-13831,'shi'=>-13658,'shou'=>-13611,'shu'=>-13601,'shua'=>-13406,'shuai'=>-13404,'shuan'=>-13400,'shuang'=>-13398,'shui'=>-13395,'shun'=>-13391,'shuo'=>-13387,'si'=>-13383,'song'=>-13367,'sou'=>-13359,'su'=>-13356,'suan'=>-13343,'sui'=>-13340,'sun'=>-13329,'suo'=>-13326,
        'ta'=>-13318,'tai'=>-13147,'tan'=>-13138,'tang'=>-13120,'tao'=>-13107,'te'=>-13096,'teng'=>-13095,'ti'=>-13091,'tian'=>-13076,'tiao'=>-13068,'tie'=>-13063,'ting'=>-13060,'tong'=>-12888,'tou'=>-12875,'tu'=>-12871,'tuan'=>-12860,'tui'=>-12858,'tun'=>-12852,'tuo'=>-12849,
        'wa'=>-12838,'wai'=>-12831,'wan'=>-12829,'wang'=>-12812,'wei'=>-12802,'wen'=>-12607,'weng'=>-12597,'wo'=>-12594,'wu'=>-12585,
        'xi'=>-12556,'xia'=>-12359,'xian'=>-12346,'xiang'=>-12320,'xiao'=>-12300,'xie'=>-12120,'xin'=>-12099,'xing'=>-12089,'xiong'=>-12074,'xiu'=>-12067,'xu'=>-12058,'xuan'=>-12039,'xue'=>-11867,'xun'=>-11861,
        'ya'=>-11847,'yan'=>-11831,'yang'=>-11798,'yao'=>-11781,'ye'=>-11604,'yi'=>-11589,'yin'=>-11536,'ying'=>-11358,'yo'=>-11340,'yong'=>-11339,'you'=>-11324,'yu'=>-11303,'yuan'=>-11097,'yue'=>-11077,'yun'=>-11067,
        'za'=>-11055,'zai'=>-11052,'zan'=>-11045,'zang'=>-11041,'zao'=>-11038,'ze'=>-11024,'zei'=>-11020,'zen'=>-11019,'zeng'=>-11018,'zha'=>-11014,'zhai'=>-10838,'zhan'=>-10832,'zhang'=>-10815,'zhao'=>-10800,'zhe'=>-10790,'zhen'=>-10780,'zheng'=>-10764,'zhi'=>-10587,'zhong'=>-10544,'zhou'=>-10533,'zhu'=>-10519,'zhua'=>-10331,'zhuai'=>-10329,'zhuan'=>-10328,'zhuang'=>-10322,'zhui'=>-10315,'zhun'=>-10309,'zhuo'=>-10307,'zi'=>-10296,'zong'=>-10281,'zou'=>-10274,'zu'=>-10270,'zuan'=>-10262,'zui'=>-10260,'zun'=>-10256,'zuo'=>-10254
    );

    /**
     * 将中文编码成拼音
     * @param string $utf8Data utf8字符集数据
     * @param string $sRetFormat 返回格式 [head:首字母|all:全拼音]
     * @param string $format utf8还是gbk
     * @return string
     */
    public static function encode($utf8Data, $sRetFormat='head',$format='utf8'){
        if($format=='utf8'){
            $sGBK = iconv('UTF-8', 'GBK', $utf8Data);
        }else{
            $sGBK = $utf8Data;
        }
        $aBuf = array();
        for ($i=0, $iLoop=strlen($sGBK); $i<$iLoop; $i++) {
            $iChr = ord($sGBK{$i});
            if ($iChr>160)
                $iChr = ($iChr<<8) + ord($sGBK{++$i}) - 65536;
            if ('head' === $sRetFormat)
                $aBuf[] = substr(self::zh2py($iChr),0,1);
            else
                $aBuf[] = self::zh2py($iChr);
        }
        if ('head' === $sRetFormat)
            return implode('', $aBuf);
        else
            return implode('', $aBuf);
    }

    /**
     * 中文转换到拼音(每次处理一个字符)
     * @param number $iWORD 待处理字符双字节
     * @return string 拼音
     */
    private static function zh2py($iWORD) {
        if($iWORD>0 && $iWORD<160 ) {
            return chr($iWORD);
        } elseif ($iWORD<-20319||$iWORD>-10247) {
            return '';
        } else {
            foreach (self::$_aMaps as $py => $code) {
                if($code > $iWORD) break;
                $result = $py;
            }
            return $result;
        }
    }

   
/**
 * @ 0-存数字字符串；1-小写字母字符串；2-大写字母字符串；3-大小写数字字符串；4-字符；
 *   5-数字，小写，大写，字符混合
 * @param  integer $type   [字符串的类型]
 * @param  integer $length [字符串的长度]
 * @param  integer $time   [是否带时间1-带，0-不带]
 * @return [string]  $str    [返回唯一字符串]
 */
public static function randSole($type = 0,$length = 18,$time=0){
    $str = $time == 0 ? '':date('YmdHis',time());
    switch ($type) {
        case 0:
            for((int)$i = 0;$i <= $length;$i++){
                if(mb_strlen($str) == $length){
                    $str = $str;
                }else{
                    $str .= rand(0,9);
                }
            }
            break;
        case 1:
            for((int)$i = 0;$i <= $length;$i++){
                if(mb_strlen($str) == $length){
                    $str = $str;
                }else{
                    $rand = "qwertyuioplkjhgfdsazxcvbnm";
                    $str .= $rand{mt_rand(0,26)};
                }
            }
            break;
        case 2:
            for((int)$i = 0;$i <= $length;$i++){
                if(mb_strlen($str) == $length){
                    $str = $str;
                }else{
                    $rand = "QWERTYUIOPLKJHGFDSAZXCVBNM";
                    $str .= $rand{mt_rand(0,26)};
                }
            }
            break;
        case 3:
            for((int)$i = 0;$i <= $length;$i++){
                if(mb_strlen($str) == $length){
                    $str = $str;
                }else{
                    $rand = "123456789qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM";
                    $str .= $rand{mt_rand(0,35)};
                }
            }
            break;
        case 4:
            for((int)$i = 0;$i <= $length;$i++){
                if(mb_strlen($str) == $length){
                    $str = $str;
                }else{
                    $rand = "!@#$%^&*()_+=-~`";
                    $str .= $rand{mt_rand(0,17)};
                }
            }
            break;
        case 5:
            for((int)$i = 0;$i <= $length;$i++){
                if(mb_strlen($str) == $length){
                    $str = $str;
                }else{
                    $rand = "123456789qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM!@#$%^&*()_+=-~`";
                    $str .= $rand{mt_rand(0,52)};
                }
            }
            break;
        }
        return $str;
    }

    public function authCode($input, $key) {
          # Input must be of even length.
         if (strlen($input) % 2) {
             //$input .= '0';
         }
 
         # Keys longer than the input will be truncated.
         if (strlen($key) > strlen($input)) {
             $key = substr($key, 0, strlen($input));
         }
 
        # Keys shorter than the input will be padded.
         if (strlen($key) < strlen($input)) {
             $key = str_pad($key, strlen($input), '0', STR_PAD_RIGHT);
         }
 
         # Now the key and input are the same length.
         # Zero is used for any trailing padding required.
 
         # Simple XOR'ing, each input byte with each key byte.
         $result = '';
         for ($i = 0; $i < strlen($input); $i++) {
             $result .= $input{$i} ^ $key{$i};
         }
         return $result;
     }
     /**
      * 加密
      */
       function encrypt($sessionId) {
         $hashKey = $this->base64url_encode($this->authCode($sessionId, $this->authCodeKey));
         $hashKey = $this->base64url_encode($sessionId);
         return $hashKey;
     }

     /**
      * 解密
      */
     function decrypt($hashKey) {
         $authCodeKey = '';
         $sessionId = $this->authCode($this->base64url_decode($hashKey), $this->authCodeKey);
         $sessionId = $this->base64url_decode($hashKey);
         return $sessionId;
     }
 
     // url传输需要替换部分字符
     function base64url_encode($data) {
         return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
     }
     // url传输需要替换部分字符
     function base64url_decode($data) {
         return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
     }


    /**
     * @params $com         快递公司名称拼音
     * @params $num         快递单号
     * @params $resultv2    添加此字段表示开通行政区域解析功能。0：关闭（默认），1：开通行政区域解析功能，2：开通行政解析功能并且返回出发、目的及当前城市信息
     * 
     */
    public function inquiry_express($com,$num,$resultv2=1){
            //参数设置
        $key        = $this->apiKey;			//客户授权key
        $customer   = $this->customer;			//查询公司编号
        $param = array (
            'com' => $com,			            //快递公司编码
            'num' => $num,	                    //快递单号
            'phone' => '',				        //手机号
            'from' => '',				        //出发地城市
            'to' => '',					        //目的地城市
            'resultv2' => $resultv2			    //开启行政区域解析
        );
        
        //请求参数
        $post_data = array();
        $post_data["customer"] = $customer;
        $post_data["param"] = json_encode($param);
        $sign = md5($post_data["param"].$key.$post_data["customer"]);
        $post_data["sign"] = strtoupper($sign);
        
        $url = 'http://poll.kuaidi100.com/poll/query.do';	//实时查询请求地址
        
        $params = "";
        foreach ($post_data as $k=>$v) {
            $params .= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
        }
        $post_data = substr($params, 0, -1);
        
        //发送post请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $data = str_replace("\"", '"', $result );
        $data = json_decode($data);
       
        //轨迹节点数组
        $trail_arr                  = $data->data;
        //提示信息
        $new_trail_arr['message']   = $data->message;
        //快递单当前状态，包括0在途，1揽收，2疑难，3签收，4退签，5派件，6退回，7转投 等8个状态
        $new_trail_arr['state']     = $data->state;
        //是否签收 1签收 
        $new_trail_arr['ischeck']   = $data->ischeck;
        foreach($trail_arr as $key=>$val){
            $new_trail_arr['data'][$key]['time']        = $val->time;
            $new_trail_arr['data'][$key]['context']     = $val->context;
            $new_trail_arr['data'][$key]['ftime']       = $val->ftime;
            $new_trail_arr['data'][$key]['areaCode']    = $val->areaCode;
            $new_trail_arr['data'][$key]['areaName']    = $val->areaName;
            $new_trail_arr['data'][$key]['status']      = $val->status;
        }
        return $new_trail_arr;
    }
    // 获取ip
    public function getIP(){
        global $ip;
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else $ip = "Unknow IP";
        return $ip;
    }
    /**
    * PHPExcel 导出
    * @param	title			String		excel 标题
    * @param 	colum_name		Array	一维		列名
    * @param	column_num		int			多少列
    * @param	file_name		String		文件名
    * @param	data			Array	二维		数据
    * @param	colnum			array	一维		字段名
    * */
    public function phpexcel_export($title,$colnum_name,$file_name,$data,$colnum){
        \think\Loader::import('PHPExcel.PHPExcel',EXTEND_PATH);
        $objExcel = new \PHPExcel();
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        $objExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
        $objExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        $objActSheet = $objExcel->getActiveSheet(0);
        $objActSheet->setTitle($title);//设置excel的标题
        $colnum_num = count($colnum_name);

        $lie = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','0','P','Q','R','S','T','U','V','W','X','Y','Z');

        for($i=0 ; $i < $colnum_num ; $i++){
            $objActSheet->setCellValue($lie[$i].'1',$colnum_name[$i]);
        }
        //也可以这样写 更好
        $baseRow = 2; //数据从N-1行开始往下输出 这里是避免头信息被覆盖
        foreach ( $data as $r => $d ) {
            $i = $baseRow + $r;
            for($s=0 ; $s < $colnum_num ; $s++) {
                $objExcel->getActiveSheet()->setCellValue($lie[$s] . $i, $d[$colnum[$s]]);
            }
        }
        $objExcel->setActiveSheetIndex(0);
        header('Content-Type: applicationnd.ms-excel');
        //如果文件名是中文要转换编码$filename=iconv('utf-8',"gb2312",$filename);//转换名称编码，防止乱码
        header("Content-Disposition: attachment;filename=".date('Y-m-d').$file_name.'.xls');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }
    // 过滤敏感词
    public function checkMingan($val = '',$type = 1){
        $minArr = model("Mingan")->getArr();
        if($type == 1){
            $m = 0;
            $minganMsg = '';
            for($i=0;$i<count($minArr);$i++){
                if(substr_count($val,$minArr[$i])>0){
                    $m++;
                    $minganMsg = $minArr[$i];
                }
            }
            // $res = $m > 0 ? false : true;
            if($m > 0){
                return ['status'=>0,'msg'=>$minganMsg];
            }else{
                return ['status'=>1,'msg'=>$val];
            }
        }else{
            return preg_replace($minArr,'***', $val);
        }
    }

    /**
     * 获取描述信息
     * @param  [type] $val [description]
     * @return [type]      [description]
     */
    public function  getDesc($val){

        return mb_substr(noHtml($val), 0, 60)."...";//截取80个汉字

    }
    /**
     * 去除html标签以及空格
     * @param  [type] $val [description]
     * @return [type]      [description]
     */
    public function noHtml($val){
        $content_01 = $val;//从数据库获取富文本content
        $content_02 = htmlspecialchars_decode($content_01);//把一些预定义的 HTML 实体转换为字符
        $content_03 = str_replace("&nbsp;","",$content_02);//将空格替换成空
        $contents = strip_tags($content_03);//函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
        //$con = mb_substr($contents, 0, 100,"utf-8");//返回字符串中的前100字符串长度的字符
        return $contents;
        /*$subject = strip_tags($val);//去除html标签 
        return $subject;
        $pattern = '/\s/';//去除空白
        return preg_replace($pattern, '', $subject); */
    }

    /**
     * 判断图片网址是否完整
     * @param  [type] $val [description]
     * @return [type]      [description]
     */
    public function checkImg($val){
        if(is_array($val)){//如果是数组的话
            $data = [];
            foreach ($val as $v) {
                if(strpos($v,'http') !== false){
                    $data[] = $v;
                }else{
                    $data[] = APP_URL.$v;
                }
            }
            return $data;
        }else{
            if(strpos($val,',') !== false){
                $val = explode(",",trim($val));
                if(strpos($val[0],'http') !== false){
                    return $val[0];
                }else{
                    return APP_URL.$val[0];
                }
            }
            if(strpos($val,'http') !== false){
                return $val;
            }else{
                return APP_URL.$val;
            }
        }
    }

    /**
     * 将数组转换为字符串
     *
     * @param	array	$data		数组
     * @return	string	返回字符串，如果，data为空，则返回空
     */
    public function array2string($Array){
        if(empty($Array)) return false;
        $Return='';
        $NullValue="^^^";
        foreach ($Array as $Key => $Value) {
            if(is_array($Value))
                $ReturnValue='^^array^'.array2string($Value);
            else
                $ReturnValue=(strlen($Value)>0)?$Value:$NullValue;
            $Return.=urlencode(base64_encode($Key)) . '|' . urlencode(base64_encode($ReturnValue)).'||';
        }
        return urlencode(substr($Return,0,-2));
    }

    /**
     * 将字符串转换为数组
     *
     * @param	string	$data	字符串
     * @return	array	返回数组格式，如果，data为空，则返回空数组
     */
    public function string2array($String){
        if(empty($String)) return false;
        $Return=array();
        $String=urldecode($String);
        $TempArray=explode('||',$String);
        $NullValue=urlencode(base64_encode("^^^"));
        foreach ($TempArray as $TempValue) {
            list($Key,$Value)=explode('|',$TempValue);
            $DecodedKey=base64_decode(urldecode($Key));
            if($Value!=$NullValue) {
                $ReturnValue=base64_decode(urldecode($Value));
                if(substr($ReturnValue,0,8)=='^^array^')
                    $ReturnValue=string2array(substr($ReturnValue,8));
                $Return[$DecodedKey]=$ReturnValue;
            }
            else
                $Return[$DecodedKey]=NULL;
        }
        return $Return;
    }
    //GBK转化为UTF-8
    public function convertUTF8($str){
        if(empty($str)) return '';
        return  iconv('gb2312', 'utf-8', $str);
    }
    /**
     * 图片等比缩放函数
     * @param  $imgurl 图片路径
     * @param  $ratio  缩小倍数
     * @param  $autocut 是否自动裁剪 默认裁剪，当高度或宽度有一个数值为0是，自动关闭
     * @param  $smallpic 无图片是默认图片路径
     */
    public function thumb_eqratio_zoom($imgurl, $ratio = 2 ,$autocut = 1, $smallpic = 'nopic.png') {
        if(empty($imgurl)) return APP_URL."/public/".$smallpic;
        $imgurl = str_replace(APP_URL, '', $imgurl);
        $imgurl = str_replace("http://daishu.test.coco3g.com", '', $imgurl);
        $imgurl = str_replace("http://daishu.coco3g.net", '', $imgurl);
        if(!extension_loaded('gd') || strpos($imgurl, '://')) return APP_URL.'/'.$imgurl;

        if (substr($imgurl, 0, 1) == '/') {
            $imgpath = str_replace('/upload/',ROOT_PATH.'upload/',$imgurl);
        }else{
            $imgpath = str_replace('upload/',ROOT_PATH.'upload/',$imgurl);
        }
        if(!file_exists($imgpath) || $imgpath == ROOT_PATH.'upload/') return APP_URL."/public/".$smallpic;

        list($width_t, $height_t, $type, $attr) = getimagesize($imgpath);
        if ($width_t < 500 || $height_t < 500) {
            return APP_URL.'/'.$imgurl;
        }
        $width =  round($width_t / $ratio);
        $height = round($height_t / $ratio);
        $newimgurl = dirname($imgpath).'/thumb_'.$width.'_'.$height.'_'.basename($imgpath);
        if(file_exists($newimgurl)) return str_replace(ROOT_PATH.'upload/','/upload/', $newimgurl);

        $image = \think\Image::open($imgpath);
        if($autocut){
            //将图片裁剪为400x400并保存为corp.jpg
            $isok = $image->thumb($width, $height,\think\Image::THUMB_CENTER)->save($newimgurl);
            //$isok = $image->crop($width, $height)->save($newimgurl);
        }
        else{
            // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
            $isok = $image->thumb($width, $height)->save($newimgurl);
        }
        return $isok ? str_replace(ROOT_PATH.'upload/','/upload/', $newimgurl) : $imgurl;
    }

    /**
     * 生成缩略图函数
     * @param  $imgurl 图片路径
     * @param  $width  缩略图宽度
     * @param  $height 缩略图高度
     * @param  $autocut 是否自动裁剪 默认裁剪，当高度或宽度有一个数值为0是，自动关闭
     * @param  $smallpic 无图片是默认图片路径
     */
    public function thumb($imgurl, $width = 100, $height = 100 ,$autocut = 1, $smallpic = 'nopic.png') {
        if(empty($imgurl)) return APP_URL."/public/".$smallpic;
        if(!extension_loaded('gd') || strpos($imgurl, '://')) return $imgurl;

        if (substr($imgurl, 0, 1) == '/') {
            $imgpath = str_replace('/upload/',ROOT_PATH.'upload/',$imgurl);
        }else{
            $imgpath = str_replace('upload/',ROOT_PATH.'upload/',$imgurl);
        }
        if(!file_exists($imgpath) || $imgpath == ROOT_PATH.'upload/') return APP_URL."/public/".$smallpic;

        list($width_t, $height_t, $type, $attr) = getimagesize($imgpath);
        if($width>=$width_t || $height>=$height_t) return $imgurl;
        $newimgurl = dirname($imgpath).'/thumb_'.$width.'_'.$height.'_'.basename($imgpath);
        if(file_exists($newimgurl)) return str_replace(ROOT_PATH.'upload/','/upload/', $newimgurl);

        $image = \think\Image::open($imgpath);
        if($autocut){
            //将图片裁剪为400x400并保存为corp.jpg
            $isok = $image->thumb($width, $height,\think\Image::THUMB_CENTER)->save($newimgurl);
            //$isok = $image->crop($width, $height)->save($newimgurl);
        }
        else{
            // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
            $isok = $image->thumb($width, $height)->save($newimgurl);
        }
        return $isok ? str_replace(ROOT_PATH.'upload/','/upload/', $newimgurl) : $imgurl;
    }

    /**
     * 字符截取 支持UTF8/GBK
     * @param $string
     * @param $length
     * @param $dot
     */
    public function str_cut($string, $length, $dot = '...') {
        $strlen = strlen($string);
        if($strlen <= $length) return $string;
        $string = str_replace(array(' ','&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
        $strcut = '';
        if(1==1) {
            // if(strtolower(CHARSET) == 'utf-8') {
            $length = intval($length-strlen($dot)-$length/3);
            $n = $tn = $noc = 0;
            while($n < strlen($string)) {
                $t = ord($string[$n]);
                if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1; $n++; $noc++;
                } elseif(194 <= $t && $t <= 223) {
                    $tn = 2; $n += 2; $noc += 2;
                } elseif(224 <= $t && $t <= 239) {
                    $tn = 3; $n += 3; $noc += 2;
                } elseif(240 <= $t && $t <= 247) {
                    $tn = 4; $n += 4; $noc += 2;
                } elseif(248 <= $t && $t <= 251) {
                    $tn = 5; $n += 5; $noc += 2;
                } elseif($t == 252 || $t == 253) {
                    $tn = 6; $n += 6; $noc += 2;
                } else {
                    $n++;
                }
                if($noc >= $length) {
                    break;
                }
            }
            if($noc > $length) {
                $n -= $tn;
            }
            $strcut = substr($string, 0, $n);
            $strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
        } else {
            $dotlen = strlen($dot);
            $maxi = $length - $dotlen - 1;
            $current_str = '';
            $search_arr = array('&',' ', '"', "'", '“', '”', '—', '<', '>', '·', '…','∵');
            $replace_arr = array('&amp;','&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;',' ');
            $search_flip = array_flip($search_arr);
            for ($i = 0; $i < $maxi; $i++) {
                $current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
                if (in_array($current_str, $search_arr)) {
                    $key = $search_flip[$current_str];
                    $current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
                }
                $strcut .= $current_str;
            }
        }
        return $strcut.$dot;
    }

    /**
     * 根据首字母排序
     * @param  array  $data [description]
     * @param  string $key  [description]
     * @return [type]       [description]
     */
    public function character($data = [],$key = 'title'){
        require_once(ROOT_PATH."extend/Character.php");
        $character = new \Character();
        $res = $character->groupByInitials($data,$key);
        return $res;
    }

    //阿里短信发送
    public function dayu_sms($phone = '',$code = '',$temp = 'SMS_140105319'){
        require_once(ROOT_PATH."extend/dayu/api_demo/SmsDemo.php");
        $demo = new \SmsDemo(
            config('SMS_NAME'),
            config('SMS_PASS')
        );
        // $demo = new \SmsDemo("LTAIpRZUf7noc8We","xgrQ5gX5kLxckSWgARuWrEAjs4aYz6");
        $res = $demo->sendSms("阿里云短信测试专用",'SMS_89870011',$phone,["code"=>$code]);
        $res = object_array($res);
        if($res['Code'] == 'OK'){
            return ['status'=>1,'msg'=>$res['Message']];
        }else{
            return ['status'=>0,'msg'=>$res['Message']];
        }
    }

    /**
     * 删除目录及目录下所有文件或删除指定文件
     * @param str $path   待删除目录路径
     * @param int $delDir 是否删除目录，1或true删除目录，0或false则只删除文件保留目录（包含子目录）
     * @return bool 返回删除状态
     */
    public function delDirAndFile($path, $delDir = FALSE) {
        $message = "";
        $handle = opendir($path);
        if ($handle) {
            while (false !== ( $item = readdir($handle) )) {
                if ($item != "." && $item != "..") {
                    if (is_dir("$path/$item")) {
                        $msg = delDirAndFile("$path/$item", $delDir);
                        if ( $msg ){
                            $message .= $msg;
                        }
                    } else {
                        $message .= "删除文件" . $item;
                        if (unlink("$path/$item")){
                            $message .= "成功<br />";
                        } else {
                            $message .= "失败<br />";
                        }
                    }
                }
            }
            closedir($handle);
            if ($delDir){
                if ( rmdir($path) ){
                    $message .= "删除目录" . dirname($path) . "<br />";
                } else {
                    $message .= "删除目录" . dirname($path) . "失败<br />";
                }


            }
        } else {
            if (file_exists($path)) {
                if (unlink($path)){
                    $message .= "删除文件" . basename($path) . "<br />";
                } else {
                    $message .= "删除文件" + basename($path) . "失败<br />";
                }
            } else {
                $message .= "文件" + basename($path) . "不存在<br />";
            }
        }
        return $message;
    }


}
