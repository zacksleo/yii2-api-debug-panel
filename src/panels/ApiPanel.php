<?php

namespace zacksleo\yii2\debug\panels;

use yii;
use yii\debug\Panel;
use yii\base\InlineAction;

class ApiPanel extends Panel
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'API调试';
    }

    /**
     * @inheritdoc
     */
    public function getSummary()
    {
        return Yii::$app->view->render('@vendor/zacksleo/yii2-api-debug-panel/src/views/summary', ['panel' => $this]);
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        return Yii::$app->view->render('@vendor/zacksleo/yii2-api-debug-panel/src/views/detail', ['panel' => $this]);
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        $headers = Yii::$app->getRequest()->getHeaders();
        $requestHeaders = [];
        foreach ($headers as $name => $value) {
            if (is_array($value) && count($value) == 1) {
                $requestHeaders[$name] = current($value);
            } else {
                $requestHeaders[$name] = $value;
            }
        }

        $responseHeaders = [];
        foreach (headers_list() as $header) {
            if (($pos = strpos($header, ':')) !== false) {
                $name = substr($header, 0, $pos);
                $value = trim(substr($header, $pos + 1));
                if (isset($responseHeaders[$name])) {
                    if (!is_array($responseHeaders[$name])) {
                        $responseHeaders[$name] = [$responseHeaders[$name], $value];
                    } else {
                        $responseHeaders[$name][] = $value;
                    }
                } else {
                    $responseHeaders[$name] = $value;
                }
            } else {
                $responseHeaders[] = $header;
            }
        }
        if (Yii::$app->requestedAction) {
            if (Yii::$app->requestedAction instanceof InlineAction) {
                $action = get_class(Yii::$app->requestedAction->controller) . '::' . Yii::$app->requestedAction->actionMethod . '()';
            } else {
                $action = get_class(Yii::$app->requestedAction) . '::run()';
            }
        } else {
            $action = null;
        }
        unset($requestHeaders['cookie'], $requestHeaders['dnt'], $requestHeaders['cache-control'], $requestHeaders['postman-token']);
        unset($requestHeaders['connection'], $requestHeaders['content-length'], $requestHeaders['origin'], $requestHeaders['user-agent'], $requestHeaders['host']);
        unset($requestHeaders['accept-encoding']);
        unset($responseHeaders['X-Powered-By'], $responseHeaders['X-Author'], $responseHeaders['X-Debug-Tag'], $responseHeaders['Access-Control-Allow-Origin']);
        unset($responseHeaders['Access-Control-Expose-Headers']);
        return [
            'statusCode' => Yii::$app->getResponse()->getStatusCode(),
            'requestHeaders' => $requestHeaders,
            'responseHeaders' => $responseHeaders,
            'route' => Yii::$app->requestedAction ? Yii::$app->requestedAction->getUniqueId() : Yii::$app->requestedRoute,
            'action' => $action,
            'actionParams' => Yii::$app->requestedParams,
            'requestBody' => Yii::$app->getRequest()->getRawBody() == '' ? [] : [
                'Content Type' => Yii::$app->getRequest()->getContentType(),
                'Decoded to Params' => Yii::$app->getRequest()->getBodyParams(),
            ],
            'FILES' => empty($_FILES) ? [] : $_FILES,
            'RESPONSE' => Yii::$app->response->data,
            'SERVER' => empty($_SERVER) ? [] : $_SERVER,
        ];
    }
}
