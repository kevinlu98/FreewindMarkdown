<?php

namespace TypechoPlugin\FreewindMarkdown;

use Typecho\Plugin\PluginInterface;
use Typecho\Widget\Helper\Form;
use Typecho\Widget\Helper\Form\Element\Radio;
use Utils\Helper;

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * Freewind主题的Markdown编辑器，当然也可以单独使用，支持Markdown、TeX(KaTeX) 数据公式、时序图、流程图，此外我还定义了一些短代码，大家有兴趣可以下载下来看看
 *
 * @package Freewind Markdown
 * @author Mr丶冷文
 * @version 1.0.0
 * @link https://kevinlu98.cn
 */
class Plugin implements PluginInterface
{
    const RADIO_ENABLE = 1;
    const RADIO_DISABLE = 0;

    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     */
    public static function activate()
    {
        \Typecho\Plugin::factory('admin/write-post.php')->bottom = __CLASS__ . '::render';
        \Typecho\Plugin::factory('admin/header.php')->header = __CLASS__ . '::header';
        \Typecho\Plugin::factory('Widget\Base\Contents')->contentEx = __CLASS__ . '::parse';
        \Typecho\Plugin::factory('Widget\Archive')->header = __CLASS__ . '::indexHeader';
        \Typecho\Plugin::factory('Widget\Archive')->footer = __CLASS__ . '::indexFooter';
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     */
    public static function deactivate()
    {
    }

    /**
     * 获取插件配置面板
     *
     * @param Form $form 配置面板
     */
    public static function config(Form $form)
    {
        $elementMathJax = new Radio(
            'is_available_mathjax',
            [self::RADIO_DISABLE => _t('不开启'), self::RADIO_ENABLE => _t('开启')],
            self::RADIO_DISABLE, _t('是否开启 TeX(KaTeX) 数学公式支持'),
            ''
        );
        $form->addInput($elementMathJax);
        $elementFlowChart = new Radio(
            'is_available_flowchart',
            [self::RADIO_DISABLE => _t('不开启'), self::RADIO_ENABLE => _t('开启')],
            self::RADIO_DISABLE, _t('是否开启 流程图 支持'),
            ''
        );
        $form->addInput($elementFlowChart);
        $elementSequenceDiagram = new Radio(
            'is_available_sequencediagram',
            [self::RADIO_DISABLE => _t('不开启'), self::RADIO_ENABLE => _t('开启')],
            self::RADIO_DISABLE, _t('是否开启 时序图 支持'),
            ''
        );
        $form->addInput($elementSequenceDiagram);
        $elementCode = new Radio(
            'is_available_code',
            [self::RADIO_DISABLE => _t('不开启'), self::RADIO_ENABLE => _t('开启')],
            self::RADIO_DISABLE,
            _t('是否开启 Freewind短代码 支持'),
            _t('编辑器内置一些短代码支持，开启后可直接使用')
        );
        $form->addInput($elementCode);
        $importJq = new Radio(
            'auto_import_jquery',
            [self::RADIO_DISABLE => _t('不开启'), self::RADIO_ENABLE => _t('开启')],
            self::RADIO_DISABLE,
            _t('是否自动导入jquery'),
            _t('如果你所用的主题没有使用到jquery,请开启此选项')
        );
        $form->addInput($importJq);
        $importFont = new Radio(
            'auto_import_font',
            [self::RADIO_DISABLE => _t('不开启'), self::RADIO_ENABLE => _t('开启')],
            self::RADIO_DISABLE,
            _t('是否自动导入FontAwesome'),
            _t('如果你所用的主题没有使用到FontAwesome,请开启此选项')
        );
        $form->addInput($importFont);
        $importAplayer = new Radio(
            'auto_import_aplyer',
            [self::RADIO_DISABLE => _t('不开启'), self::RADIO_ENABLE => _t('开启')],
            self::RADIO_DISABLE,
            _t('是否自动导入Aplayer及MetingJS'),
            _t('如果你所用的主题没有使用到Aplayer及MetingJS,请开启此选项')
        );
        $form->addInput($importAplayer);
    }

    /**
     * 个人用户的配置面板
     *
     * @param Form $form
     */
    public static function personalConfig(Form $form)
    {
    }

    public static function indexHeader($header)
    {
        ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/css/markdown.extend.min.css">
        <?php if (Helper::options()->plugin('FreewindMarkdown')->auto_import_aplyer): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/APlayer/APlayer.min.css">
    <?php endif; ?>
        <?php if (Helper::options()->plugin('FreewindMarkdown')->auto_import_font): ?>
        <link href="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <?php endif; ?>
        <?php if (Helper::options()->plugin('FreewindMarkdown')->is_available_mathjax): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/katex/katex.min.css">
    <?php endif; ?>
        <?php
    }

    public static function indexFooter()
    {
        ?>
        <?php if (Helper::options()->plugin('FreewindMarkdown')->auto_import_jquery): ?>
        <script src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/jquery/jquery-3.5.1.min.js"></script>
    <?php endif ?>

        <?php if (Helper::options()->plugin('FreewindMarkdown')->is_available_mathjax): ?>
        <script defer src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/katex/katex.min.css"></script>
        <script defer src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/katex/contrib/auto-render.min.js"
                onload="renderMathInElement(document.body);"></script>
    <?php endif; ?>
        <?php if (Helper::options()->plugin('FreewindMarkdown')->is_available_flowchart): ?>
        <script src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/editormd/lib/raphael.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/editormd/lib/flowchart.min.js"></script>
    <?php endif; ?>
        <?php if (Helper::options()->plugin('FreewindMarkdown')->is_available_sequencediagram): ?>
        <script src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/editormd/lib/underscore.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/editormd/lib/sequence-diagram.min.js"></script>
    <?php endif; ?>
        <?php if (Helper::options()->plugin('FreewindMarkdown')->auto_import_aplyer): ?>
        <script src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/APlayer/APlayer.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/MetingJS/Meting.js"></script>
    <?php endif; ?>
        <script>
            $(function () {
                <?php if (Helper::options()->plugin('FreewindMarkdown')->is_available_code): ?>
                $("#fw-article-content").on('click', '.fwh .fwthead', function () {
                    $(this).parent().children('.fwthead').removeClass('fwcurrent')
                    $(this).addClass('fwcurrent')
                    $(this).parent().parent().find('.fwtbody').hide()
                    $(this).parent().parent().find(`.fwtbody-${$(this).data('target')}`).stop().fadeIn()
                })
                $("#fw-article-content .fwtab .fwh .fwthead:first-child").click()
                <?php endif; ?>
                <?php if (Helper::options()->plugin('FreewindMarkdown')->is_available_flowchart): ?>
                $('#fw-article-content code.lang-flow').each((index, element) => {

                    chart = flowchart.parse($(element).text());
                    $(element).parent().after(`<div id="canvas-${index}"></div>`).remove()
                    chart.drawSVG(`canvas-${index}`);
                })
                <?php endif; ?>
                <?php if (Helper::options()->plugin('FreewindMarkdown')->is_available_sequencediagram): ?>
                $('#fw-article-content code.lang-seq').each((index, element) => {
                    let code = $(element).text()
                    $(element).parent().after(`<div id="seq-${index}">${code}</div>`).remove()
                    $(`#seq-${index}`).sequenceDiagram({theme: 'simple'});
                })
                <?php endif; ?>

            })
        </script>
        <?php
    }

    public static function parse($content)
    {
        if (Helper::options()->plugin('FreewindMarkdown')->is_available_code) {
            if (strpos($content, '{fwcline') !== false) {
                $content = preg_replace('/{fwcline[ ]*start="([\s\S]*?)"[ ]*end="([\s\S]*?)"[ ]*}[\s\S]*?{\/fwcline[ ]*}/', '<div class="fwcline" style="background-image: linear-gradient(-45deg,transparent 0,transparent 10%,$2 0,$2 40%,transparent 0,transparent 60%,$1 0,$1 90%,transparent 0,transparent 100%);"></div>', $content);
            }
            if (strpos($content, '{fwbili') !== false) {
                $content = preg_replace('/{fwbili[ ]*bvid="([\s\S]*?)"[ ]*bvnu="([\s\S]*?)"[ ]*}([\s\S]*?){\/fwbili[ ]*}/', '<iframe class="fwbili" src="//player.bilibili.com/player.html?bvid=$1&page=$2"></iframe>', $content);
            }
            if (strpos($content, '{fwmusic') !== false) {
                $content = preg_replace('/{fwmusic[ ]*source="([\s\S]*?)"[ ]*type="([\s\S]*?)"[ ]*id="([\s\S]*?)"[ ]*}[\s\S]*?{\/fwmusic[ ]*}/', '<meting-js
	server="$1"
	type="$2"
	id="$3">
</meting-js>', $content);
            }
            if (strpos($content, '{fwcode') !== false) {
                $content = preg_replace('/{fwcode[ ]*type="([\s\S]*?)"[ ]*}(<br>)?([\s\S]*?)(<br>)?{\/fwcode[ ]*}/', '<div class="fwcode fwcode-$1">$3</div>', $content);
            }
            if (strpos($content, '{fwalert') !== false) {
                $content = preg_replace('/{fwalert[ ]*type="([\s\S]*?)"[ ]*}(<br>)*([\s\S]*?)(<br>)*{\/fwalert[ ]*}/', '<div class="fwalert fwalert-$1">$3</div>', $content);
            }
            if (strpos($content, '{fwbtn') !== false) {
                $content = preg_replace('/{fwbtn[ ]*type="(.*?)"[ ]*url="(.*?)"[ ]*}(<br>)*{icon="(.*?)"[ ]*}(.*?)(<br>)*{\/fwbtn[ ]*}/', '<a class="fwbtn fwbtn-$1" href="$2" target="_blank"><i class="fa $4"></i>$5</a>', $content);
            }
            if (strpos($content, '{fwgroup') !== false) {
                $content = preg_replace('/{fwgroup[ ]*title="(.*?)"[ ]*}(<br>)?([\s\S]*?)(<br>)?{\/fwgroup[ ]*}/', '<div class="fwgroup pos-rlt"><div class="fwgroup-title pos-abs">$1</div>$3</div>', $content);
            }
            if (strpos($content, '{fwtab') !== false) {
                $content = preg_replace('/{fwthead[ ]*target="(.*?)"[ ]*}(<br>)*([\s\S]*?)(<br>)*{\/fwthead[ ]*}/', '<div class="fwthead" data-target="$1">$3</div>', $content);
                $content = preg_replace('/{fwtbody[ ]*target="(.*?)"[ ]*}(<br>)?([\s\S]*?)(<br>)?{\/fwtbody[ ]*}/', '<div class="fwtbody fwtbody-$1">$3</div>', $content);
                $content = preg_replace('/{fwh[ ]*}(<br>)*([\s\S]*?)(<br>)*{\/fwh[ ]*}/', '<div class="fwh">$2</div>', $content);
                $content = preg_replace('/{fwb[ ]*}(<br>)*([\s\S]*?)(<br>)*{\/fwb[ ]*}/', '<div class="fwb">$2</div>', $content);
                $content = preg_replace('/{fwtab[ ]*}(<br>)*([\s\S]*?)(<br>)*{\/fwtab[ ]*}/', '<div class="fwtab">$2</div>', $content);
            }
        }
        return '<div id="fw-article-content">' . $content . '</div>';
    }

    /**
     * 插件实现方法
     *
     * @access public
     * @return void
     */
    public static function render(): void
    {

        ?>
        <script src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/editormd/editormd.js"></script>
        <?php if (Helper::options()->plugin('FreewindMarkdown')->is_available_code): ?>
        <script src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/APlayer/APlayer.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/MetingJS/Meting.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/layer/layer.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/color/xncolorpicker.min.js"></script>
    <?php endif; ?>
        <script>
            $(function () {
                $('#wmd-button-bar').remove()
                let text = $('#text').clone()
                $('#wmd-editarea').remove()
                $('#wmd-preview').after(text)
                text.wrap('<div id="freewind-markdown"></div>')
                editormd('freewind-markdown', {
                    height: 700,
                    <?php if (Helper::options()->plugin('FreewindMarkdown')->is_available_mathjax): ?>
                    tex: true,                   // 开启科学公式TeX语言支持，默认关闭
                    <?php endif ?>
                    <?php if (Helper::options()->plugin('FreewindMarkdown')->is_available_flowchart): ?>
                    flowChart: true,             // 开启流程图支持，默认关闭
                    <?php endif ?>
                    <?php if (Helper::options()->plugin('FreewindMarkdown')->is_available_sequencediagram): ?>
                    sequenceDiagram: true,       // 开启时序/序列图支持，默认关闭,
                    <?php endif ?>
                    searchReplace: true,
                    toolbarAutoFixed: false,
                    imageUpload: true,
                    imageFormats: ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                    imageUploadURL: "<?php echo __TYPECHO_PLUGIN_DIR__?>/FreewindMarkdown/upload.php",
                    path: 'https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/editormd/lib/',
                    toolbarIcons: [
                        "undo", "redo", "|",
                        "bold", "del", "italic", "quote", "|",
                        "h1", "h2", "h3", "h4", "h5", "h6", "|",
                        "list-ul", "list-ol", "hr", "|",
                        "link", "image", "code", "code-block", "table", "datetime", "|",
                        <?php if (Helper::options()->plugin('FreewindMarkdown')->is_available_code): ?>
                        "fw-message", "fw-alert", "fw-tab", 'fw-group', 'lw-linkbtn', 'lw-color-line', 'lw-music', 'lw-bilibili', "|",
                        <?php endif; ?>
                        "watch", "preview", "fullscreen", "clear", "search"
                    ],
                    <?php if (Helper::options()->plugin('FreewindMarkdown')->is_available_code): ?>
                    toolbarIconsClass: {
                        'fw-alert': 'fa-comment',
                        'fw-message': "fa-bell",
                        'fw-tab': "fa-columns",
                        'fw-group': 'fa-newspaper-o',
                        'lw-linkbtn': 'fa-paper-plane',
                        'lw-color-line': 'fa-arrows-h',
                        'lw-music': 'fa-music',
                        'lw-bilibili': 'fa-video-camera'
                    },
                    toolbarHandlers: {
                        'fw-message': function (cm) {
                            let content = `<div class="fw-layer-content" style="width: 300px;">
                                            <div class="fw-form-item">
                                            <label>提示类型：</label>
                                            <select id="fw-alert-type" style="width: 235px;">
                                            <option value="success">成功</option>
                                            <option value="error">失败</option>
                                            <option value="info">信息</option>
                                            <option value="warning">警告</option>
                                            </select>
                                            </div>
                                            <div class="fw-form-item"><label>提示内容：</label><textarea style="outline:none;width: 235px;resize: none;height: 100px;" id="fw-alert-content">${cm.getSelections()['0']}</textarea></div>
                                            </div>`
                            let idx = layer.open({
                                type: 1,
                                width: 600,
                                title: '插入提示',
                                btn: ['确定', '取消'],
                                content: content,
                                btn1: () => {
                                    cm.replaceSelection(`{fwalert type="${$("#fw-alert-type").val()}"}${$("#fw-alert-content").val()}{/fwalert}`);
                                    cm.focus()
                                    layer.close(idx)
                                }
                            })
                        },
                        'fw-alert': function (cm, icon, cursor, selection) {
                            let content = `<div class="fw-layer-content" style="width: 300px;">
                                            <div class="fw-form-item">
                                            <label>消息类型：</label>
                                            <select id="fw-msg-type"  style="width: 235px;">
                                            <option value="success">成功</option>
                                            <option value="error">失败</option>
                                            <option value="info">信息</option>
                                            <option value="warning">警告</option>
                                            </select>
                                            </div>
                                            <div class="fw-form-item"><label  style="vertical-align: top">提示内容：</label><textarea style="outline:none;width: 235px;resize: none;height: 100px;" id="fw-msg-content">${cm.getSelections()['0']}</textarea></div>
                                            </div>`
                            let idx = layer.open({
                                type: 1,
                                width: 600,
                                title: '插入消息',
                                btn: ['确定', '取消'],
                                content: content,
                                btn1: () => {
                                    cm.replaceSelection(`\n{fwcode type="${$("#fw-msg-type").val()}"}\n${$("#fw-msg-content").val()}\n{/fwcode}\n`);
                                    cm.focus()
                                    layer.close(idx)
                                }
                            })
                        },
                        'fw-tab': function (cm) {
                            let content = `<div class="fw-layer-content">
                                            <div class="fw-form-item">
                                            <label>tab栏列：</label>
                                            <input type="number" id="fw-tab-col" value="2">
                                            </div>
                                            </div>`
                            let idx = layer.open({
                                type: 1,
                                width: 600,
                                title: '插入tab栏',
                                btn: ['确定', '取消'],
                                content: content,
                                btn1: () => {
                                    let col = $("#fw-tab-col").val()
                                    if (col < 2) {
                                        layer.msg('请至少选择2列', {icon: 2})
                                        return false;
                                    }
                                    col = col < 2 ? 2 : col;
                                    let text = '\n{fwtab}\n{fwh}\n'
                                    for (let i = 0; i < col; i++) {
                                        text += `{fwthead target="${i + 1}"} tab栏${i + 1} {/fwthead}\n`
                                    }
                                    text += '{/fwh}\n{fwb}\n'
                                    for (let i = 0; i < col; i++) {
                                        text += `{fwtbody target="${i + 1}"}\n内容${i + 1}\n{/fwtbody}\n`
                                    }
                                    text += '{/fwb}\n{/fwtab}\n'
                                    cm.replaceSelection(text);
                                    cm.focus()
                                    layer.close(idx)
                                }
                            })
                        },
                        'fw-group': function (cm) {
                            let content = `<div class="fw-layer-content" style="width: 300px;">
                                            <div class="fw-form-item">
                                            <label>分组名称：</label>
                                            <input type="text" id="fw-group-title" style="width: 235px;">
                                            </div>
                                            <div class="fw-form-item"><label style="vertical-align: top">分组内容：</label> <textarea style="width: 235px;outline: none;height: 100px;" id="fw-group-cn">${cm.getSelections()[0]}</textarea></div>
                                            </div>`
                            let idx = layer.open({
                                type: 1,
                                width: 600,
                                title: '插入分组卡片',
                                btn: ['确定', '取消'],
                                content: content,
                                btn1: () => {
                                    let btncontent = `\n{fwgroup title="${$("#fw-group-title").val()}"}\n${$("#fw-group-cn").val()}\n{/fwgroup}\n`
                                    cm.replaceSelection(btncontent);
                                    cm.focus()
                                    layer.close(idx)
                                }
                            })
                        },
                        'lw-linkbtn': function (cm) {
                            let content = `<div class="fw-layer-content" style="width: 300px;">
                                            <div class="fw-form-item">
                                            <label>按钮类型：</label>
                                            <select id="fw-btn-type" style="width: 235px;">
                                            <option value="normal">正常</option>
                                            <option value="success">成功</option>
                                            <option value="error">失败</option>
                                            <option value="info">信息</option>
                                            <option value="warning">警告</option>
                                            </select>
                                            </div>
                                            <div class="fw-form-item"><label>按钮内容：</label> <input type="text" style="width: 235px;" value="${cm.getSelections()[0]}" id="fw-btn-text"></div>
                                            <div class="fw-form-item"><label>按钮图标：</label> <input type="text" style="width: 235px;"  id="fw-btn-icon" placeholder="不填写默认为下载图标"></div>
                                            <div class="fw-form-item"><label>跳转链接：</label> <input type="text" style="width: 235px;"  id="fw-btn-url"></div>
                                            <p style="margin: 0;padding: 0;font-size: 12px;color: #777;">图标为 <a href="https://www.thinkcmf.com/font/font_awesome/icons.html" target="_blank">fontawesome字体图标</a>，拷贝图标名称即可</p>
                                            </div>`
                            let idx = layer.open({
                                type: 1,
                                width: 600,
                                title: '插入跳转按钮',
                                btn: ['确定', '取消'],
                                content: content,
                                btn1: () => {
                                    let url = $("#fw-btn-url").val();
                                    url = url ? url : '#'
                                    let icon = $("#fw-btn-icon").val();
                                    icon = icon ? icon : 'fa-download';
                                    let btncontent = `{fwbtn type="${$("#fw-btn-type").val()}" url="${url.replace('/', '\\/')}"}{icon="${icon}"}${$("#fw-btn-text").val()}{/fwbtn}`
                                    cm.replaceSelection(btncontent);
                                    cm.focus()
                                    layer.close(idx)
                                }
                            })
                        },
                        'lw-color-line': function (cm) {
                            let content = `<div class="fw-layer-content" style="width: 300px">
                                                <div class="fw-form-item">
                                                <label>开始颜色：</label>
                                                <input id="color-start" value="#01D0FF" style="height: 26px;" >
                                                <div id="start-btn" style="border: 1px solid #000; display: inline-block;vertical-align: bottom"></div>
                                                </div>
                                                <div class="fw-form-item">
                                                <label>结束颜色：</label>
                                                <input id="color-end"  value="#FC3E85" style="height: 26px;">
                                                <div id="end-btn" style="border: 1px solid #000; display: inline-block;vertical-align: bottom"></div>
                                                </div>
                                                </div>`
                            let idx = layer.open({
                                type: 1,
                                width: 600,
                                title: '插入彩色分割符',
                                btn: ['确定', '取消'],
                                content: content,
                                btn1: () => {
                                    let btncontent = `\n{fwcline start="${$("#color-start").val()}" end="${$("#color-end").val()}"}{/fwcline}\n`
                                    cm.replaceSelection(btncontent);
                                    cm.focus()
                                    layer.close(idx)
                                }
                            })
                            new XNColorPicker({
                                color: "#01D0FF",
                                selector: "#start-btn",
                                showprecolor: true,//显示预制颜色
                                prevcolors: null,//预制颜色，不设置则默认
                                showhistorycolor: true,//显示历史
                                historycolornum: 16,//历史条数
                                format: 'hex',//rgba hex hsla,初始颜色类型
                                showPalette: false,//显示色盘
                                show: false, //初始化显示
                                lang: 'cn',// cn 、en
                                colorTypeOption: 'single,linear-gradient,radial-gradient',//
                                onError: function (e) {

                                },
                                onCancel: function (color) {

                                },
                                onChange: function (color) {
                                    $('#color-start').val(color.color.hex).css('color', color.color.hex)
                                },
                                onConfirm: function (color) {
                                    $('#color-start').val(color.color.hex)
                                }
                            })
                            new XNColorPicker({
                                color: "#FC3E85",
                                selector: "#end-btn",
                                showprecolor: true,//显示预制颜色
                                prevcolors: null,//预制颜色，不设置则默认
                                showhistorycolor: true,//显示历史
                                historycolornum: 16,//历史条数
                                format: 'hex',//rgba hex hsla,初始颜色类型
                                showPalette: false,//显示色盘
                                show: false, //初始化显示
                                lang: 'cn',// cn 、en
                                colorTypeOption: 'single,linear-gradient,radial-gradient',//
                                onError: function (e) {

                                },
                                onCancel: function (color) {

                                },
                                onChange: function (color) {
                                    $('#color-end').val(color.color.hex).css('color', color.color.hex)
                                },
                                onConfirm: function (color) {
                                    $('#color-end').val(color.color.hex)
                                }
                            })

                        },
                        'lw-music': function (cm) {
                            let content = `<div class="fw-layer-content" style="width: 300px;">
                                            <div class="fw-form-item">
                                            <label>音乐来源：</label>
                                            <select id="music-source" style="width: 220px;">
                                            <option value="netease" selected>网易云音乐</option>
                                            <option value="tencent">腾讯音乐</option>
                                            </select>
                                            </div>
                                            <div class="fw-form-item">
                                            <label>音乐类型：</label>
                                            <select id="music-type" style="width: 220px;">
                                            <option value="playlist" selected>歌单</option>
                                            <option value="song">歌曲</option>
                                            </select>
                                            </div>
                                            <div class="fw-form-item">
                                            <label>音乐 &nbsp;I D：</label>
                                            <input id="music-id" placeholder="歌曲ID/歌单ID" style="width: 220px;">
                                            </div>
                                            </div>`
                            let idx = layer.open({
                                type: 1,
                                width: 600,
                                title: '插入音乐',
                                btn: ['确定', '取消'],
                                content: content,
                                btn1: () => {
                                    let ms = $("#music-source").val()
                                    let mt = $("#music-type").val()
                                    let mi = $("#music-id").val()
                                    let btncontent = `\n{fwmusic source="${ms}" type="${mt}" id="${mi}"}{/fwmusic}\n`
                                    cm.replaceSelection(btncontent);
                                    cm.focus()
                                    layer.close(idx)
                                }
                            })
                        },
                        'lw-bilibili': function (cm) {
                            let content = `<div class="fw-layer-content" style="width: 300px;">
                                            <div class="fw-form-item">
                                            <label>视频BVID：</label>
                                            <input type="text" id="fw-bvid" style="width: 220px;">
                                            </div>
                                            <div class="fw-form-item">
                                            <label>视频剧集：</label>
                                            <input type="number" id="fw-bvnu" value="1" style="width: 220px;">
                                            </div>
                                            </div>`
                            let idx = layer.open({
                                type: 1,
                                width: 600,
                                title: '插入B站视频',
                                btn: ['确定', '取消'],
                                content: content,
                                btn1: () => {
                                    let bvnu = $("#fw-bvnu").val() ? $("input#fw-bvnu").val() : 1;
                                    let btncontent = `\n{fwbili bvid="${$("#fw-bvid").val()}" bvnu="${bvnu}"}{/fwbili}\n`
                                    cm.replaceSelection(btncontent);
                                    cm.focus()
                                    layer.close(idx)
                                }
                            })
                        }
                    },
                    <?php endif; ?>
                    onload: function () {
                        $('.editormd-preview .editormd-preview-container').attr('id', 'fw-article-content')
                        $('.editormd-preview-close-btn').hide()

                        $("#fw-article-content").on('click', '.fwh .fwthead', function () {
                            $(this).parent().children('.fwthead').removeClass('fwcurrent')
                            $(this).addClass('fwcurrent')
                            $(this).parent().parent().find('.fwtbody').hide()
                            $(this).parent().parent().find(`.fwtbody-${$(this).data('target')}`).stop().fadeIn()
                        })
                    },
                    onfullscreen: function () {
                        this.editor.css('zIndex', 999)
                    }
                })


            })
        </script>
        <?php
    }

    public static function header($header): string
    {
        if (strpos($_SERVER['SCRIPT_NAME'], "write-post.php")) {
            $header = $header . '<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/editormd/css/editormd.min.css">';
            if (Helper::options()->plugin('FreewindMarkdown')->is_available_code) {
                $header = $header . '<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/css/markdown.extend.css">';
                $header = $header . '<link href="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">';
                $header = $header . '<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/kevinlu98/FreewindMarkdown@1.0/lib/APlayer/APlayer.min.css">';
            }
            $header = $header . '
            <style>
                .fcolorpicker {
                    z-index: 198910151!important;
                }
                .fcolorpicker .current-color-value input {
                    padding: 0;
                }
                .fw-layer-content {
                    padding: 0 20px;
                }
                .fw-layer-content .fw-form-item {
                    margin: 10px 0;
                    color: #777;
                    font-size: 12px;
                }
            </style>
            ';
        }
        return $header;
    }
}
