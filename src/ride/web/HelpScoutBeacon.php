<?php

namespace ride\web;

use ride\library\config\Config;
use ride\library\event\Event;
use ride\library\mvc\Response;
use ride\library\mvc\view\HtmlView;


class HelpScoutBeacon  {

    public function __construct(Response $response) {
        $this->response = $response;
    }

    public function enqueueJavascript(Event $event, Config $config) {

        $formId = $config->get('helpscout.beacon.form.id');
        $docsDomain = 'http://' . $config->get('helpscout.beacon.subdomain') . '.helpscoutdocs.com';

        $view = $this->response->getView();
        if ($view instanceof HtmlView) {
            $view->addInlineJavascript('
            });
            $(function (e, o, n) {
                window.HSCW = o, window.HS = n, n.beacon = n.beacon || {};
                var t = n.beacon;
                t.userConfig = {}, t.readyQueue = [], t.config = function (e) {
                    this.userConfig = e
                },
                    t.ready = function (e) {
                        this.readyQueue.push(e)
                    },
                    o.config = {
                        docs: {enabled: 0, baseUrl: "' . $docsDomain . '"},
                        contact: {enabled: 1, formId: "' . $formId . '"}
                    };
                var r = e.getElementsByTagName("script")[0], c = e.createElement("script");
                c.type = "text/javascript", c.async = !0, c.src = "https://djtflbt20bdde.cloudfront.net/",
                    r.parentNode.insertBefore(c, r)
            }(document, window.HSCW || {}, window.HS || {}
            )); $(function() { ');
        }
    }
}