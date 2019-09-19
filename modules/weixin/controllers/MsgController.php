<?php
namespace app\modules\weixin\controllers;

use app\common\components\BaseWebController;
use app\common\services\UrlService;
use app\models\book\Book;
use Yii;
use yii\log\FileTarget;


class MsgController extends BaseWebController {

    public function actionIndex( ){
        if( !$this->checkSignature() ){
            $this->record_log( "校验错误" );
            return 'error signature ~~';
        }

        if( array_key_exists('echostr',$_GET) && $_GET['echostr']){
            return $_GET['echostr'];
        }

        $xml_data = file_get_contents("php://input");
        $this->record_log( "[xml_data]:". $xml_data );
        if( !$xml_data ){
            return 'error xml';
        }

        $xml_obj = simplexml_load_string( $xml_data, 'SimpleXMLElement', LIBXML_NOCDATA);

        $from_username = $xml_obj->FromUserName;
        $to_username = $xml_obj->ToUserName;
        $msg_type = $xml_obj->MsgType;

        $res = [ 'type'=>'text','data'=>$this->defaultTip() ];
        switch ( $msg_type ){
            case "text":
                if( $xml_obj->Content == "账号"){
                    $res = [ 'type'=>'text','data'=> '用户名：aa，密码：123456' ];
                }else{
                    $kw = trim( $xml_obj->Content );
                    $res = $this->search( $kw );
                }
                break;
            case "event":
                $res = $this->parseEvent( $xml_obj );
                break;
            default:
                break;
        }

        switch($res['type']){
            case "rich":
                return $this->richTpl($from_username,$to_username,$res['data']);
                break;
            default:
                return $this->textTpl($from_username,$to_username,$res['data']);
        }


        return "hi";
    }

    private function search( $kw ){
        $query = Book::find()->where([ 'status' => 1 ]);
        $where_name = [ 'LIKE','name','%'.strtr( $kw ,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
        $where_tag = [ 'LIKE','tags','%'.strtr( $kw ,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
        $query->andWhere([ 'OR',$where_name,$where_tag ]);
        $res = $query->orderBy([ 'id' => SORT_DESC ])->limit( 3 )->all();
        $data = $res?$this->getRichXml( $res ):$this->defaultTip();
        $type = $res?"rich":"text";
        return ['type' => $type ,"data" => $data];
    }

    private function parseEvent( $dataObj ){
        $resType = "text";
        $resData = $this->defaultTip();
        $event = $dataObj->Event;
        switch($event){
            case "subscribe":

                $resData = $this->subscribeTips();
                break;
            case "CLICK":
                $eventKey = trim($dataObj->EventKey);
                switch($eventKey){
                }
                break;
        }
        return [ 'type'=>$resType,'data'=>$resData ];
    }

    private function textTpl( $from_username,$to_username,$content )
    {
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        <FuncFlag>0</FuncFlag>
        </xml>";
        return sprintf($textTpl, $from_username, $to_username, time(), "text", $content);
    }

    private function richTpl( $from_username ,$to_username,$data){
        $tpl = <<<EOT
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
%s
</xml>
EOT;
        return sprintf($tpl, $from_username, $to_username, time(), $data);
    }

    private function getRichXml( $list ){
        $article_count = count( $list );
        $article_content = "";
        foreach($list as $_item){
            $tmp_description = mb_substr( strip_tags( $_item['summary'] ),0,20,"utf-8" );
            $tmp_pic_url = UrlService::buildPicUrl( "book",$_item['main_image'] );
            $tmp_url = UrlService::buildMUrl( "/product/info",[ 'id' => $_item['id'] ] );
            $article_content .= "
<item>
<Title><![CDATA[{$_item['name']}]]></Title>
<Description><![CDATA[{$tmp_description}]]></Description>
<PicUrl><![CDATA[{$tmp_pic_url}]]></PicUrl>
<Url><![CDATA[{$tmp_url}]]></Url>
</item>";
        }

        $article_body = "<ArticleCount>%s</ArticleCount>
<Articles>
%s
</Articles>";
        return sprintf($article_body,$article_count,$article_content);
    }


    private function defaultTip(){
        $resData = <<<EOT
没找到你想要的东西（：\n
EOT;
        return $resData;
    }


    private function subscribeTips(){
        $resData = <<<EOT
感谢您的关注，
输入关键字,可以搜索书籍
EOT;
        return $resData;
    }

    public function checkSignature(){
        $signature = trim( $this->get("signature","") );
        $timestamp = trim( $this->get("timestamp","") );
        $nonce = trim( $this->get("nonce","") );
        $tmpArr = array( \Yii::$app->params['weixin']['token'],$timestamp,$nonce );
        sort( $tmpArr,SORT_STRING );
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr ==  $signature ){
            return true;
        }else{
            return false;
        }
    }

    public   function record_log($msg){
        $log = new FileTarget();
        $log->logFile = Yii::$app->getRuntimePath() . "/logs/weixin_msg_".date("Ymd").".log";
        $request_uri = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';
        $log->messages[] = [
            "[url:{$request_uri}][post:".http_build_query($_POST)."] [msg:{$msg}]",
            1,
            'application',
            microtime(true)
        ];
        $log->export();
    }

}
