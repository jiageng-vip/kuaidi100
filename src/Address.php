<?php


namespace Jiageng\Kuaidi100;

use GuzzleHttp\Exception\GuzzleException;
use Jiageng\Kuaidi100\Exceptions\HttpException;
use Jiageng\Kuaidi100\Exceptions\InvalidArgumentException;
use Jiageng\Kuaidi100\Models\AddressRequest;
use Jiageng\Kuaidi100\Models\LabelRequest;

/**
 * 地址解析
 * Class CloudPrint
 * @package Jiageng\Kuaidi100
 */
class Address extends Base
{
    protected string $path = '/address/resolution';

    /**
     * 解析地址
     * @param AddressRequest $request
     * @return string
     * @throws HttpException
     * @throws GuzzleException
     */
    public function resolution(AddressRequest $request)
    {
        $url = $this->endpoint. $this->path;

        $param = $request->toArray();

        $t = time();

        $this->sign = $sign = strtoupper(md5(json_encode($param).$t.$this->key.$this->options['secret']));

        $params = [
            'method' => 'order',
            'key' => $this->options['key'],
            'sign' => $sign,
            't' => $t,
            'param' => json_encode($param)
        ];

        try {
            $response = $this->getHttpClient()->request('POST', $url, [
                'form_params' => $params,
            ])->getBody()->getContents();
            return $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

}
