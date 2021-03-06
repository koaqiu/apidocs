<?php
include_once 'config.php';
$parser = new ApiParser(API_ROOT_DIR);
$modules= $parser->getApiModule();
$errorCodeList = $parser->getErrCodeList();

?>
<html>
<head>
<title>API文档说明</title>
<meta charset="utf-8">
<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
<link href="asset/default.css?v=0.4" rel="stylesheet"/>
    <style>
        dl.sample{
            margin:2px 0px;
        }
        dl.sample dt{
            display:inline-block;
            width:100px;

        }
        dl.sample dd{
            display:inline-block;
        }
        dl.sample dd:after{
            clear:both;
            display:block;
            content:" ";
        }
        h4.panel-title a{ display:block;}
    </style>
    <link rel="stylesheet" href="asset/prism.css" data-noprefix />
	<script src="asset/prism.js" data-manual></script>
</head>
<body>
<div class="container">
    <div class="row">
        <h1 class="apidoc-subject">API说明<a id="anchor"></a></h1>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="api-filter">
                <label><input type="checkbox" checked data-filter="console" /> 后台</label>
                <label><input type="checkbox" checked data-filter="frontend" /> 前端</label>
                <label><input type="checkbox" checked data-filter="common" /> 公共</label>
            </div>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <?php
                if(!empty($modules)){
                    $index = 1;
                    foreach($modules as $apiCatalog=>$detail){
                        ?>
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $index; ?>" aria-expanded="true" aria-controls="collapse-<?php echo $index; ?>">
            <?php echo $apiCatalog ?></a>
        </h4>
    </div>
    <div id="collapse-<?php echo $index; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
        <ul class="apilist list-group">
                        <?php
                        $index++;
                        foreach($detail['apis'] as $apiId=>$apiName){
                            echo '<li data-method="'.$apiId.'" class="list-group-item"><a data-method="'.$apiId.'" href="#'.$apiId.'"><span>'.$apiId.'</span><p>'.$apiName.'</p></a></li>';
                        }
                        ?>
        </ul>
    </div>
</div>
                        <?php
                    }
                }
                ?>
        </div>
            <div class="errorcode-panel">
            <h2 class="apilist-error"><div>错误代码说明</div></h2>
            <ul class="errorcode">
                <?php
                if(!empty($errorCodeList)){
                    foreach($errorCodeList as $error){
                        echo '<li ><span>'.$error['code'].'</span>'.$error['message'].'</li>';
                    }
                }
                ?>
            </ul>
            </div>
        </div>
        <div class="col-sm-9 api-detail-panel">
            <h1 id="api_name_doctitle"></h1>
            <p id="api_description"></p>
            <h2>请求网关</h2>
            <div class="well">
            <p>请求格式：JSON格式请求</p>
            <p>响应格式：JSON格式</p>
            <p>请求方式：POST</p>
                <?php
                if(API_GATEWAY) {
                    ?>
                    <p>API网关地址：<?php echo API_GATEWAY?></p>
                    <?php
                }
                if(APIMOCK_URL){
                    ?>
                    <p>mock地址：<a target="_blank"  data-api-base="<?php echo APIMOCK_URL?>mock.php?api=" class="api_mock"><?php echo APIMOCK_URL?>mock.php?api=<span data-api-id="">/接口方法</span></a></p>
                        <?php
                    }
                ?>
            <div id="accessLevel-2" class="alert alert-warning hide" role="alert"><strong>注意</strong> 如果传递了ACCESS_TOKEN但是已经失效，此API会报错</div>
            <div id="accessLevel-1" class="alert alert-danger hide" role="alert"><strong>警告</strong> 此API需要合法的ACCESS_TOKEN</div>
            <p>公共参数 <a href="javascript:;" id="btn-public-params-collapse">显示</a></p>
            <div id="tb-public-params" class="collapse">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#comm-struct" aria-controls="comm-struct" role="tab" data-toggle="tab">结构说明</a></li>
                <li role="presentation"><a href="#comm-objc" aria-controls="comm-objc" role="tab" data-toggle="tab">Objective-C</a></li>
                <li role="presentation"><a href="#comm-java" aria-controls="comm-java" role="tab" data-toggle="tab">Java</a></li>
                <li role="presentation"><a href="#comm-ts" aria-controls="comm-ts" role="tab" data-toggle="tab">TypeScript</a></li>
            </ul>

            <div class="tab-content comm-request-struct">
                <div role="tabpanel" class="tab-pane active" id="comm-struct">
            <table class="table table-bordered table-striped">
            <thead>
            <tr>
            <td>名称</td>
            <td>类型</td>
            <td>是否必须</td>
            <td>描述</td>
            </tr>
            </thead>
            <tbody >
                <tr>
                    <td>sign</td>
                    <td>String</td>
                    <td>true</td>
                    <td>加密签名字串，sign的生成规则：
                        <p>
                             将所有参数按字母a-z顺序排序，以Key+Value的形式串起来，头尾再加上appSecret值，例现在有这些参数：

                            <dl class="sample">
                                <dt>Key</dt>
                                <dd>Value</dd>
                        </dl><dl class="sample">
                                <dt>method</dt>
                                <dd>member.register</dd>
                        </dl><dl class="sample">
                                <dt>appkey</dt>
                                <dd>1001</dd>
                        </dl><dl class="sample">
                                <dt>access_token</dt>
                                <dd>12345</dd>
                        </dl><dl class="sample">
                                <dt>appsecret</dt>
                                <dd>xxxxx</dd>
                            </dl>
                            按Key升序，将Key+Value的顺序排序来串，头尾加上appsecret的值就是：
                            <br/>
                            xxxxxaccess_token12345appkey1001methodmember.registerxxxxx
                        <br/>
                            然后将上面这个字串进行md5加密，再转为大写，就是sign的值
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>appkey</td>
                    <td>String</td>
                    <td>true</td>
                    <td>如1001</td>
                </tr><tr >
                    <td>method</td>
                    <td>String</td>
                    <td>true</td>
                    <td>接口名称，<strong><span data-api-id=""></span></strong></td>
                </tr>

                <tr>
                    <td>access_token</td>
                    <td>String</td>
                    <td id="api_auth"></td>
                    <td>用户token</td>
                </tr>
                <tr>
                    <td>locale</td>
                    <td>String</td>
                    <td>false</td>
                    <td>服务端响应的语种，目前支持zh_CN, en_US两种，不指定或指定错时全默认zh_CN</td>
                </tr>
                <tr>
                    <td>version</td>
                    <td>String</td>
                    <td>false</td>
                    <td>如：0.1.0。版本号，三段式，每段最大值254，即最大版本号254.254.254，最小0.0.1</td>
                </tr>
            </tbody>
            </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="comm-objc">
                    <div class="json-renderer" data-lang='objectivec'>Objective-C 代码</div>
                </div>
                <div role="tabpanel" class="tab-pane" id="comm-java">
                    <div class="json-renderer" data-lang='java'>Java 代码</div>
                </div>
                <div role="tabpanel" class="tab-pane" id="comm-ts">
                    <div class="json-renderer" data-lang='TypeScript'>TS 代码</div>
                </div>
            </div>

            </div>
        </div>
            <div id="api_detail_section" class="hide">
                <h2>业务级请求参数</h2>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#req-struct" aria-controls="req-struct" role="tab" data-toggle="tab">结构说明</a></li>
                    <li role="presentation"><a href="#req-objc" aria-controls="req-objc" role="tab" data-toggle="tab">Objective-C</a></li>
                    <li role="presentation"><a href="#req-java" aria-controls="req-java" role="tab" data-toggle="tab">Java</a></li>
                    <li role="presentation"><a href="#req-ts" aria-controls="req-ts" role="tab" data-toggle="tab">TypeScript</a></li>
                </ul>
                <div class="tab-content request-struct">
                <div role="tabpanel" class="tab-pane active" id="req-struct">
                <table class="table table-bordered table-striped">
                <thead>
                <tr>
                <td>参数</td>
                <td>类型</td>
                <td>是否必须</td>
                <td>默认值</td>
                <td>描述</td>
                </tr>
                </thead>
                <tbody  id="request_params">
                </tbody>
                </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="req-objc">
                    <div class="json-renderer" data-lang='objectivec'>Objective-C 代码</div>
                </div>
                <div role="tabpanel" class="tab-pane" id="req-java">
                    <div class="json-renderer" data-lang='java'>Java 代码</div>
                </div>
                <div role="tabpanel" class="tab-pane" id="req-ts">
                    <div class="json-renderer" data-lang='TypeScript'>TS 代码</div>
                </div>
                </div>

                <h2>响应结果</h2>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#res-struct" aria-controls="res-struct" role="tab" data-toggle="tab">结构说明</a></li>
                    <li role="presentation"><a href="#res-objc" aria-controls="res-objc" role="tab" data-toggle="tab">Objective-C</a></li>
                    <li role="presentation"><a href="#res-java" aria-controls="res-java" role="tab" data-toggle="tab">Java</a></li>
                    <li role="presentation"><a href="#res-ts" aria-controls="res-ts" role="tab" data-toggle="tab">TypeScript</a></li>
                </ul>
                <div class="tab-content response-struct">
                    <div role="tabpanel" class="tab-pane active" id="res-struct">
                    <table class="table  table-striped api-response-table">
                    <thead>
                    <tr>
                    <td>名称</td>
                    <td>类型</td>
                    <td>示例值</td>
                    <td>描述</td>
                    </tr>
                    </thead>
                    <tbody  id="response_result">
                    <tr>
                        <td>status</td>
                        <td>Integer</td>
                        <td>0</td>
                        <td>状态码</td>
                    </tr>
                    </tbody>
                    </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="res-objc">
                    <div class="json-renderer" data-lang='objectivec'>Objective-C 代码</div>
                </div>
                <div role="tabpanel" class="tab-pane" id="res-java">
                    <div class="json-renderer" data-lang='java'>Java 代码</div>
                </div>
                <div role="tabpanel" class="tab-pane" id="res-ts">
                    <div class="json-renderer" data-lang='TypeScript'>TS 代码</div>
                </div>
                </div>

                <h2>响应示例</h2>
                <div class="json-renderer"><pre class="line-numbers"><code class="language-json" id="response_sample"></code></pre></div>
                <h2>异常示例(通用格式，非针对当前接口)</h2>
                <pre id="error_response_sample"></pre>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>
<script type="text/javascript" src="asset/parser.js?v=0.31"></script>
<script>
$('.api-filter input[type=checkbox]').change((e)=>{
    let checkbox = $(e.target);
    let filter = checkbox.data('filter');
    //console.log(checkbox.prop('checked'), checkbox.data('filter'));
    let apis = $('li[data-method]').filter((i,item)=>{
        switch(filter){
            case "console":return /^console\./ig.test($(item).data('method'));
            case "common":return /^common\./ig.test($(item).data('method'));
            case "frontend":return /^frontend\./ig.test($(item).data('method'));
        }
        return true;
    });
    if(checkbox.prop('checked')){
        apis.removeClass('hide').addClass('show');
    }else{
        apis.removeClass('show').addClass('hide');
    }
    $('.panel').each((index, panel)=>{
        if($(panel).find('li.hide').length == $(panel).find('li').length){
            $(panel).removeClass('show').addClass('hide');
        }else{
            $(panel).removeClass('hide').addClass('show');
        }
    });
});
</script>
</body>
</html>
