<?php

namespace common\data;

/**
 * 操作结果
 * @author wutong
 * @date 2015/12/30 2:06
 */
class ResultCode
{

    /**
     * 消息
     * @var string
     */
    public $message = "";

    /**
     * 是否成功
     * @var int
     */
    public $code = 200;

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return ResultCode
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * 附加的数据
     * @var mixed
     */
    public $data = null;

    /**
     * 实例
     * @var self | null
     */
    public static $instance = null;

    /**
     * 避免实例化
     */
    private function __construct()
    {

    }

    /**
     * asCode
     * @param null $code
     * @return $this
     * @author lin  2021/3/10
     */
    public function asCode($code=null){
        if($code){
            $this->code=$code;
        }
        return $this;
    }

    /**
     * 初始化
     * @return ResultCode
     */
    public static function getInstance()
    {
        self::$instance = new self;
        return self::$instance;
    }

    /**
     * 应用结果
     * @param string $message 消息
     * @param int    $code 代码
     * @param array  $data 传过去的自定义数据
     * @return ResultCode
     */
    public static function apply($message = "操作成功", $code = 200, $data = null)
    {
        $result = self::getInstance();
        $result->message = $message;
        $result->code = $code;
        $result->data = $data;
        return $result;
    }

    /**
     * 操作成功
     * @param string $message 消息
     * @param array  $data 数据
     * @return ResultCode
     */
    public static function success($message = "操作成功", $data = null)
    {
        return self::apply($message, 200, $data);
    }

    /**
     * 操作失败
     * @param string $message 消息
     * @param int    $code 代码
     * @param array  $data 数据
     * @return ResultCode
     */
    public static function failure($message = "操作失败", $code = 500, $data = null)
    {
        return self::apply($message, $code, $data);
    }

    /**
     * 返回结果信息
     * @return array
     */
    public function asArray()
    {
        return [
            'message' => $this->message,
            'code'    => $this->code,
            'data'    => $this->data,
        ];
    }

    /**
     * 获得消息
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 设置消息
     * @param string $message 消息内容
     * @return ResultCode
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * 是否成功
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->code === 0;
    }

    /**
     * 设置是否成功
     * @return ResultCode
     */
    public function setSuccess()
    {
        $this->code = 0;
        return $this;
    }

    /**
     * 获取数据
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 设置数据
     * @param mixed $data 要设置的数据
     * @return ResultCode
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

	public function __toString() {
		return json_encode($this->asArray(),true);
	}
}
